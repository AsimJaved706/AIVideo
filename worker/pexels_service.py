import os
import requests

def fetch_stock_video(keyword: str, file_path: str, api_key: str = None) -> str:
    """
    Search Pexels API for a video matching the keyword and download it to file_path.
    """
    if not api_key:
        api_key = os.getenv("PEXELS_API_KEY")
        
    if not api_key:
        raise ValueError("Pexels API Key is missing. Please add it in Settings.")
        
    url = "https://api.pexels.com/videos/search"
    headers = {
        "Authorization": api_key
    }
    params = {
        "query": keyword,
        "per_page": 1,
        "orientation": "portrait"
    }
    
    response = requests.get(url, headers=headers, params=params)
    response.raise_for_status()
    
    data = response.json()
    if not data.get("videos"):
        print(f"Warning: No stock video found for '{keyword}'")
        return None
        
    video = data["videos"][0]
    
    # get HD video file link ideally
    video_files = video["video_files"]
    hd_file = next((f for f in video_files if f.get("quality") == "hd"), video_files[0])
    download_url = hd_file["link"]
    
    # Download the video
    vid_response = requests.get(download_url, stream=True)
    with open(file_path, "wb") as f:
        for chunk in vid_response.iter_content(chunk_size=8192):
            f.write(chunk)
            
    return file_path

def fetch_stock_image_url(keyword: str, api_key: str = None) -> str:
    """
    Search Pexels API for an image matching the keyword and return the URL.
    """
    if not api_key:
        api_key = os.getenv("PEXELS_API_KEY")
        
    if not api_key:
        raise ValueError("Pexels API Key is missing. Please add it in Settings.")
        
    url = "https://api.pexels.com/v1/search"
    headers = {
        "Authorization": api_key
    }
    params = {
        "query": keyword,
        "per_page": 1,
        "orientation": "portrait"
    }
    
    response = requests.get(url, headers=headers, params=params)
    response.raise_for_status()
    
    data = response.json()
    if not data.get("photos"):
        print(f"Warning: No stock image found for '{keyword}'")
        return None
        
    photo = data["photos"][0]
    # return the high-resolution portrait URL
    return photo["src"]["portrait"]
