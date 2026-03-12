import os
from google import genai

client = genai.Client(api_key=os.environ.get("GEMINI_API_KEY"))
print("Listing all textual and vision models...")
try:
    models = list(client.models.list())
    for m in models:
        # Note: the new genai SDK might return google.genai.types.Model objects.
        name = getattr(m, 'name', str(m))
        if 'image' in name or 'imagen' in name:
            print(f"Found match: {name}")
    print("Available model count:", len(models))
except Exception as e:
    print(f"Error: {e}")
