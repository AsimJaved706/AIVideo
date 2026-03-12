import os
import sys

# Load GEMINI_API_KEY from Laravel .env
env_path = r"C:\Users\WT\.gemini\antigravity\scratch\ai_video_platform\backend\.env"
with open(env_path, "r") as f:
    for line in f:
        if line.startswith("GEMINI_API_KEY="):
            os.environ["GEMINI_API_KEY"] = line.split("=")[1].strip()

import gemini_service
try:
    path = gemini_service.generate_veo_video("A cat driving a car", "/tmp/test_veo.mp4")
    print(f"Success! {path}")
except Exception as e:
    import traceback
    traceback.print_exc()
