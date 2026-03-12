import os
import requests
import time
from runwayml import RunwayML

def generate_runway_video(
    image_url: str,
    prompt: str,
    output_path: str,
    api_key: str = None,
    duration: int = 5,
    visual_style: str = "documentary",
    camera_motion: str = "gentle_handheld",
    lighting_style: str = "natural_daylight",
    color_palette: str = "neutral",
    framing: str = "medium_shot",
    negative_prompt: str = "",
) -> str:
    """
    Generates a video from an image and a text prompt using RunwayML Gen-3 Alpha Turbo.
    Downloads the completed video to output_path.
    """
    if not api_key:
        api_key = os.environ.get("RUNWAYML_API_KEY")

    if not api_key:
        raise ValueError("RunwayML API Key is missing. Please add it in Settings.")

    print(f"Initializing RunwayML client for prompt: '{prompt}'")
    client = RunwayML(api_key=api_key)

    enhanced_prompt = (
        f"{visual_style} vertical 9:16 live-action video, single continuous shot, realistic human motion, "
        f"camera motion {camera_motion}, lighting {lighting_style}, color palette {color_palette}, framing {framing}, "
        f"natural skin texture, real-world depth, consistent subject identity, physically plausible movement, "
        f"stable composition, no CGI look, no animation artifacts, no warped faces, no jitter, no surreal morphing. "
        f"Scene: {prompt}"
    )

    if negative_prompt:
        enhanced_prompt += f" Avoid: {negative_prompt}"

    try:
        # Create the task
        task = client.image_to_video.create(
            model="gen3a_turbo",
            prompt_image=image_url,
            prompt_text=enhanced_prompt,
            duration=int(duration),
            ratio="768:1280",
        )
        task_id = task.id
        print(f"RunwayML Task created successfully: {task_id}. Polling for completion...")

        # Poll for completion
        while True:
            # wait 5 seconds between polls
            time.sleep(5)
            
            status_obj = client.tasks.retrieve(task_id)
            current_status = status_obj.status
            
            if current_status == "SUCCEEDED":
                result_urls = status_obj.output
                if result_urls and len(result_urls) > 0:
                    video_url = result_urls[0]
                    print(f"Video generated successfully! Downloading from {video_url}...")
                    
                    # Download video
                    vid_response = requests.get(video_url, stream=True)
                    vid_response.raise_for_status()
                    with open(output_path, "wb") as f:
                        for chunk in vid_response.iter_content(chunk_size=8192):
                            f.write(chunk)
                            
                    print(f"Downloaded RunwayML video to {output_path}")
                    return output_path
                else:
                    raise Exception("Task succeeded but no output URLs found in the payload.")
                    
            elif current_status == "FAILED":
                error_msg = getattr(status_obj, 'error', 'Unknown generation error')
                raise Exception(f"RunwayML video generation failed: {error_msg}")
            
            elif current_status == "CANCELLED":
                raise Exception("RunwayML video generation was cancelled.")
                
            else:
                # Still RUNNING or PENDING
                print(f"Task status: {current_status}... waiting.")
                
    except Exception as e:
        print(f"RunwayML Pipeline Exception: {e}")
        raise
