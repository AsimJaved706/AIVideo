from fastapi import FastAPI, BackgroundTasks, HTTPException
from fastapi.staticfiles import StaticFiles
from pydantic import BaseModel
import os
import json
from typing import Optional

# Import services
import gemini_service
import audio_service
import image_service
import render_service
import runwayml_service
import s3_service
import api_client

app = FastAPI(title="AI Video Worker", description="Worker API for processing AI Video Tasks")

# Ensure /tmp exists (or Use Windows temp path, but script uses /tmp literally)
os.makedirs("/tmp", exist_ok=True)
app.mount("/files", StaticFiles(directory="/tmp"), name="files")

class GenerateRequest(BaseModel):
    video_id: int
    campaign_id: int
    topic: str
    video_length: int = 60
    storage_preference: str = "local" # local or s3
    gemini_api_key: Optional[str] = None
    elevenlabs_api_key: Optional[str] = None
    runwayml_api_key: Optional[str] = None
    voice_id: str = "21m00Tcm4TlvDq8ikWAM"
    voice_model: str = "eleven_multilingual_v2"
    voice_output_format: str = "mp3_44100_128"
    fallback_language: str = "en"
    voice_stability: float = 0.35
    voice_similarity_boost: float = 0.8
    voice_style: float = 0.15
    voice_speaker_boost: bool = True
    script_tone: str = "natural"
    visual_style: str = "documentary"
    seed_image_style: str = "photorealistic"
    image_lighting_style: str = "natural_daylight"
    image_color_palette: str = "neutral"
    image_framing: str = "medium_shot"
    image_negative_prompt: str = ""
    camera_motion: str = "gentle_handheld"
    runway_duration: int = 5

def get_asset_url(req: GenerateRequest, local_path: str) -> str:
    if req.storage_preference == 's3':
        url = s3_service.upload_to_s3(local_path, os.getenv("S3_BUCKET", "my-bucket"))
        if url:
            return url
    
    # Fallback to local
    filename = os.path.basename(local_path)
    return f"http://127.0.0.1:8001/files/{filename}"


def build_script_preview(script_data: dict) -> str:
    scenes = script_data.get('scenes', []) if isinstance(script_data, dict) else []
    if not scenes:
        return ""

    lines = []
    for index, scene in enumerate(scenes, start=1):
        narration = (scene.get('narration_text') or '').strip()
        if narration:
            lines.append(f"[Scene {index}] {narration}")

    return "\n\n".join(lines)

def process_video_pipeline(req: GenerateRequest):
    """
    Background task to orchestrate the video generation pipeline.
    """
    try:
        # Step 1: Generate Script
        script_path = f"/tmp/{req.video_id}_script.json"
        
        if os.path.exists(script_path):
            print("Script already exists. Loading from cache.")
            with open(script_path, "r") as f:
                script_data = json.load(f)
        else:
            api_client.update_video_status(req.video_id, "Generating Scripts")
            script_data = gemini_service.generate_video_script(
                req.topic, 
                req.video_length, 
                api_key=req.gemini_api_key,
                script_tone=req.script_tone,
                visual_style=req.visual_style,
            )
            # Save script locally
            with open(script_path, "w") as f:
                json.dump(script_data, f)

        script_preview = build_script_preview(script_data)
        api_client.update_video_status(
            req.video_id,
            "Generating Voice",
            title=script_data.get('title', ''),
            description=script_preview,
        )
            
        script_url = get_asset_url(req, script_path)
        if script_url:
            api_client.upload_media_record(req.video_id, "script", script_url, req.campaign_id)

        # Step 2: Generate Voice
        audio_paths = []
        for i, scene in enumerate(script_data.get('scenes', [])):
            text = scene.get('narration_text', '')
            if text:
                audio_file = f"/tmp/{req.video_id}_scene_{i}.mp3"
                if os.path.exists(audio_file):
                    print(f"Audio file {audio_file} exists. Skipping generation.")
                else:
                    audio_service.generate_voiceover(
                        text, 
                        audio_file, 
                        api_key=req.elevenlabs_api_key,
                        voice_id=req.voice_id,
                        model_id=req.voice_model,
                        output_format=req.voice_output_format,
                        fallback_language=req.fallback_language,
                        stability=req.voice_stability,
                        similarity_boost=req.voice_similarity_boost,
                        style=req.voice_style,
                        use_speaker_boost=req.voice_speaker_boost,
                    )
                audio_paths.append(audio_file)

        # Step 3: Generate Visuals (RunwayML + Pollinations.ai seed image)
        api_client.update_video_status(req.video_id, "Generating Visuals")
        video_paths = []
        for i, scene in enumerate(script_data.get('scenes', [])):
            keyword = scene.get('visual_description', '')
            if keyword:
                vid_file = f"/tmp/{req.video_id}_scene_{i}.mp4"
                
                if os.path.exists(vid_file):
                    print(f"Video file {vid_file} exists. Skipping generation.")
                    video_paths.append(vid_file)
                    continue
                    
                try:
                    image_data_uri = image_service.generate_stock_image_data_uri(
                        keyword,
                        style=req.seed_image_style,
                        lighting_style=req.image_lighting_style,
                        color_palette=req.image_color_palette,
                        framing=req.image_framing,
                    )
                    if not image_data_uri:
                        raise Exception("Failed to generate a seed image.")

                    res = runwayml_service.generate_runway_video(
                        image_url=image_data_uri,
                        prompt=keyword,
                        output_path=vid_file,
                        api_key=req.runwayml_api_key,
                        duration=req.runway_duration,
                        visual_style=req.visual_style,
                        camera_motion=req.camera_motion,
                        lighting_style=req.image_lighting_style,
                        color_palette=req.image_color_palette,
                        framing=req.image_framing,
                        negative_prompt=req.image_negative_prompt,
                    )
                    video_paths.append(res)
                except Exception as e:
                    with open("/tmp/runway_debug.log", "a") as logf:
                        logf.write(f"Visual Generation failed for scene {i}: {e}\n")
                    print(f"Visual Generation failed for scene {i}: {e}")
                    # Don't raise Exception to crash the whole job, just skip this scene's video
                    # The MoviePy renderer will need to be resilient to missing clips
        
        # Step 4: Render Video
        api_client.update_video_status(req.video_id, "Rendering Video")
        final_video_path = f"/tmp/{req.video_id}_final.mp4"
        render_service.render_final_video(video_paths, audio_paths, final_video_path)

        # Step 5: Upload final asset
        api_client.update_video_status(req.video_id, "Publishing Media")
        video_url = get_asset_url(req, final_video_path)
        if video_url:
            api_client.upload_media_record(req.video_id, "final_video", video_url, req.campaign_id)

        # Finished
        api_client.update_video_status(req.video_id, "Ready", video_url)

    except Exception as e:
        import traceback
        err_msg = traceback.format_exc()
        api_client.update_video_status(req.video_id, "Failed", error_message=err_msg)
        print(f"Error processing video {req.video_id}: {err_msg}")

@app.post("/generate")
def start_generation(req: GenerateRequest, background_tasks: BackgroundTasks):
    background_tasks.add_task(process_video_pipeline, req)
    return {"message": "Video generation pipeline started in the background", "video_id": req.video_id}

@app.get("/")
def read_root():
    return {"status": "AI Video Worker is running"}

@app.get("/test-models")
def get_models():
    """Returns available Gemini models."""
    from google import genai
    import os
    try:
        client = genai.Client(api_key=os.environ.get("GEMINI_API_KEY"))
        models = [m.name for m in client.models.list() if 'image' in m.name or 'imagen' in m.name]
        return {"count": len(models), "models": models}
    except Exception as e:
        return {"error": str(e)}

if __name__ == "__main__":
    import uvicorn
    uvicorn.run("main:app", host="0.0.0.0", port=8001, reload=True)
