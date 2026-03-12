import os
os.environ['RUNWAYML_API_SECRET'] = 'test'
from runwayml import RunwayML

client = RunwayML()

print("--- client ---")
print(dir(client))

if hasattr(client, 'image_to_video'):
    print("--- image_to_video ---")
    print(dir(client.image_to_video))

