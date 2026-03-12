import os
from runwayml import RunwayML

os.environ['RUNWAYML_API_SECRET'] = 'fake_key'
client = RunwayML()

try:
    task = client.image_to_video.create(
        model='gen3a_turbo',
        prompt_text='A cat driving a car'
    )
except Exception as e:
    print(repr(e))
