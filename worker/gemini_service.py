import os
import json
import time
import requests
from google import genai
from google.genai import types

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
    You are a senior short-form video writer.
    Write a {script_tone}, human-sounding short video script about "{topic}" for about {video_length_sec} seconds.

    Requirements:
    - Conversational tone, simple spoken English, no robotic or salesy phrasing.
    - Hook in first 2-3 seconds that sounds like real speech.
    - 5 to 8 short scenes.
    - Each narration line should sound like someone talking naturally, not reading from a script.
    - Use contractions (e.g., "it's", "you're") where natural.
    - Avoid repetition, buzzwords, and generic filler.
    - Visual descriptions must be realistic, specific, and optimized for {visual_style} vertical scenes.
    - Keep each scene duration between 3 and 8 seconds.
    - Total duration_estimate should be close to {video_length_sec} seconds.

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
            temperature=0.7,
        ),
    )
    
    return json.loads(response.text)

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
