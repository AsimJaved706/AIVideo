import os
import requests

def generate_voiceover(
    text: str,
    file_path: str,
    api_key: str = None,
    voice_id: str = "21m00Tcm4TlvDq8ikWAM",
    model_id: str = "eleven_multilingual_v2",
    output_format: str = "mp3_44100_128",
    fallback_language: str = "en",
    stability: float = 0.35,
    similarity_boost: float = 0.8,
    style: float = 0.15,
    use_speaker_boost: bool = True,
) -> str:
    """
    Generate voiceover using ElevenLabs API and save to file_path.
    """
    if not api_key:
        api_key = os.getenv("ELEVENLABS_API_KEY")
        
    if not api_key:
        raise ValueError("ElevenLabs API Key is missing. Please add it in Settings.")
        
    url = f"https://api.elevenlabs.io/v1/text-to-speech/{voice_id}"
    headers = {
        "Accept": "audio/mpeg",
        "Content-Type": "application/json",
        "xi-api-key": api_key
    }
    
    normalized_text = " ".join((text or "").split())
    data = {
        "text": normalized_text,
        "model_id": model_id,
        "voice_settings": {
            "stability": max(0.0, min(1.0, float(stability))),
            "similarity_boost": max(0.0, min(1.0, float(similarity_boost))),
            "style": max(0.0, min(1.0, float(style))),
            "use_speaker_boost": bool(use_speaker_boost)
        },
        "output_format": output_format
    }
    
    response = requests.post(url, json=data, headers=headers)
    
    if response.status_code != 200:
        print(f"ElevenLabs failed: {response.text}")
        print("Falling back to free Google Text-to-Speech (gTTS)...")
        from gtts import gTTS
        tts = gTTS(text, lang=fallback_language)
        tts.save(file_path)
        return file_path
        
    with open(file_path, "wb") as f:
        f.write(response.content)
        
    return file_path
