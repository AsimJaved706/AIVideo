import requests
import os
import json

api_key = os.environ.get('RUNWAYML_API_KEY', 'test')

url = "https://api.dev.runwayml.com/v1/tasks" # sometimes it's /v1/image_to_video
url_ttv = "https://api.dev.runwayml.com/v1/text_to_video"

headers = {
    "Authorization": f"Bearer {api_key}",
    "X-Runway-Version": "2024-11-06",
    "Content-Type": "application/json"
}

payload = {
    "model": "gen3a_turbo",
    "promptText": "A cat driving a car"
}

try:
    response = requests.post(url_ttv, headers=headers, json=payload)
    print("HTTP Status TTV:", response.status_code)
    print("HTTP Response TTV:", response.text)
except Exception as e:
    print(e)
