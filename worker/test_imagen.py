import os
from google import genai

def test_imagen():
    api_key = os.environ.get("GEMINI_API_KEY")
    if not api_key:
        print("No Gemini API Key found")
        return
        
    client = genai.Client(api_key=api_key)
    try:
        response = client.models.generate_images(
            model='imagen-3.0-generate-001',
            prompt='A cat driving a car',
            config=dict(
                number_of_images=1,
                aspect_ratio="9:16",
                output_mime_type="image/jpeg",
            )
        )
        for generated_image in response.generated_images:
            print("Success, Imagen is available!")
            return
            
    except Exception as e:
        print(f"Imagen error: {e}")

if __name__ == "__main__":
    test_imagen()
