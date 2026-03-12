import os
import json
import time
import requests
from google import genai
from google.genai import types


def _normalize_narration_text(value: str) -> str:
    text = " ".join((value or "").strip().split())
    if not text:
        return ""

    # Remove common formatting artifacts that sound robotic in TTS
    text = text.replace("#", "")
    if text.startswith('"') and text.endswith('"') and len(text) > 1:
        text = text[1:-1].strip()

    if text and text[-1] not in ".!?":
        text += "."

    return text


def _normalize_visual_description(value: str) -> str:
    text = " ".join((value or "").strip().split())
    if not text:
        return ""
    return text


def _normalize_script_payload(payload: dict, target_duration: int) -> dict:
    if not isinstance(payload, dict):
        raise ValueError("Gemini returned invalid script payload.")

    scenes = payload.get("scenes") or []
    normalized_scenes = []
    for scene in scenes:
        if not isinstance(scene, dict):
            continue

        narration_text = _normalize_narration_text(scene.get("narration_text", ""))
        visual_description = _normalize_visual_description(scene.get("visual_description", ""))
        if not narration_text or not visual_description:
            continue

        try:
            duration = int(scene.get("duration_estimate", 5))
        except (TypeError, ValueError):
            duration = 5

        duration = max(3, min(8, duration))

        normalized_scenes.append(
            {
                "visual_description": visual_description,
                "narration_text": narration_text,
                "duration_estimate": duration,
            }
        )

    if not normalized_scenes:
        raise ValueError("Script generation produced no valid scenes.")

    title = " ".join(str(payload.get("title", "")).split()).strip() or "Generated Video"
    hook = _normalize_narration_text(payload.get("hook", ""))
    hashtags = payload.get("hashtags") if isinstance(payload.get("hashtags"), list) else []
    hashtags = [str(tag).strip() for tag in hashtags if str(tag).strip().startswith("#")][:6]

    # Keep total duration near user target by gently scaling scene durations.
    total_duration = sum(scene["duration_estimate"] for scene in normalized_scenes)
    if total_duration > 0 and abs(total_duration - int(target_duration)) > 5:
        scale = int(target_duration) / total_duration
        for scene in normalized_scenes:
            scene["duration_estimate"] = max(3, min(8, int(round(scene["duration_estimate"] * scale))))

    return {
        "title": title,
        "hook": hook,
        "hashtags": hashtags,
        "scenes": normalized_scenes,
    }

def generate_video_script(
    topic: str,
    video_length_sec: int = 60,
    api_key: str = None,
    script_tone: str = "natural",
    visual_style: str = "documentary",
) -> dict:
    """
    Calls Google Gemini to generate a video script JSON format.
    """
    if not api_key:
        api_key = os.getenv("GEMINI_API_KEY")
        
    if not api_key:
        raise ValueError("Google Gemini API Key is missing. Please add it in Settings.")

    client = genai.Client(api_key=api_key)

    prompt = f"""
    You are a senior short-form creator writing for spoken narration and realistic vertical video.
    Write a {script_tone}, human-sounding short video script about "{topic}" for about {video_length_sec} seconds.

    Requirements:
    - Conversational spoken English. Sound like a real person talking naturally.
    - Hook in first 2-3 seconds that sounds spontaneous, not scripted.
    - 5 to 8 short scenes.
    - Each narration line must be one natural spoken sentence (no bullet points, no stage directions).
    - Use contractions (e.g., "it's", "you're") where natural.
    - Avoid repetition, buzzwords, cliches, dramatic hype language, and generic filler.
    - Keep pacing smooth and coherent from scene to scene.
    - Visual descriptions must be realistic, specific, and optimized for {visual_style} vertical 9:16 scenes.
    - Visual descriptions should include subject, location, action, and camera framing in plain language.
    - Keep each scene duration between 3 and 8 seconds.
    - Total duration_estimate should be close to {video_length_sec} seconds.
    - No emojis. No markdown. No quotation marks around narration.

    Return strict JSON only in this schema:
    {{
        "title": "short title",
        "hook": "spoken hook",
        "hashtags": ["#tag1", "#tag2", "#tag3"],
        "scenes": [
            {{
                "visual_description": "realistic visual shot description",
                "narration_text": "natural spoken narration",
                "duration_estimate": 5
            }}
        ]
    }}
    """

    response = client.models.generate_content(
        model='gemini-2.5-flash',
        contents=prompt,
        config=types.GenerateContentConfig(
            response_mime_type="application/json",
            temperature=0.6,
        ),
    )

    raw_payload = json.loads(response.text)
    return _normalize_script_payload(raw_payload, video_length_sec)

def generate_veo_video(prompt: str, file_path: str, api_key: str = None) -> str:
    """
    Generates a video using Google Veo (veo-2.0-generate-001) based on a text prompt
    and saves the generated mp4 to file_path.
    """
    if not api_key:
        api_key = os.getenv("GEMINI_API_KEY")
        
    if not api_key:
        raise ValueError("Google Gemini API Key is missing. Please add it in Settings.")

    client = genai.Client(api_key=api_key)
    
    enhanced_prompt = (
        "Photorealistic vertical 9:16 video, natural lighting, real-world camera motion, "
        "documentary B-roll style, believable human movement, detailed textures, "
        "no CGI look, no animation artifacts. Scene: "
        f"{prompt}"
    )
    
    print(f"Starting Veo video generation for prompt: {prompt}")
    operation = client.models.generate_videos(
        model="veo-2.0-generate-001",
        source=types.GenerateVideosSource(
            prompt=enhanced_prompt,
        ),
    )
    
    # Poll until the generation is complete
    while not operation.done:
        print("Waiting for Veo video generation...")
        time.sleep(10)
        # Update operation status
        operation = client.operations.get(operation=operation)
        
    if not operation.result or not operation.result.generated_videos:
        raise Exception(f"Veo generation failed or returned no results.")
        
    uri = operation.result.generated_videos[0].video.uri
    print(f"Veo generated video URI: {uri}. Downloading...")
    
    # Download the video
    response = requests.get(uri, stream=True)
    if response.status_code != 200:
        raise Exception(f"Failed to download generated Veo video from URI. Status: {response.status_code}")
        
    with open(file_path, "wb") as f:
        for chunk in response.iter_content(chunk_size=8192):
            f.write(chunk)
            
    print(f"Veo video successfully saved to {file_path}")
    return file_path
