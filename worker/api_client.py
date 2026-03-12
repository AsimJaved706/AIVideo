import os
import requests

LARAVEL_API_URL = os.getenv("LARAVEL_API_URL", "http://127.0.0.1:8000/api")


def _base_api_url() -> str:
    base = (LARAVEL_API_URL or "").strip().rstrip("/")
    if not base:
        base = "http://127.0.0.1:8000/api"
    if not base.endswith("/api"):
        base = f"{base}/api"
    return base

def update_video_status(video_id: int, status: str, s3_url: str = None, error_message: str = None):
    url = f"{_base_api_url()}/worker/videos/{video_id}/status"
    payload = {"status": status}
    if s3_url:
        payload["s3_url"] = s3_url
    if error_message:
        payload["error_message"] = error_message
        
    headers = {"Accept": "application/json"}
    response = requests.post(url, json=payload, headers=headers, timeout=30)
    response.raise_for_status()
    return response.json()

def upload_media_record(video_id: int, media_type: str, s3_path: str, campaign_id: int = None):
    url = f"{_base_api_url()}/worker/videos/{video_id}/media"
    payload = {
        "type": media_type,
        "s3_path": s3_path,
        "campaign_id": campaign_id
    }
    headers = {"Accept": "application/json"}
    response = requests.post(url, json=payload, headers=headers, timeout=30)
    response.raise_for_status()
    return response.json()
