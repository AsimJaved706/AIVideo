import os
os.environ['GEMINI_API_KEY'] = '123'
import google.genai as genai
client = genai.Client()
if hasattr(client.models, 'generate_videos'):
    print(client.models.generate_videos.__doc__)
else:
    print("generate_videos not found. Printing all model methods:")
    print(dir(client.models))
