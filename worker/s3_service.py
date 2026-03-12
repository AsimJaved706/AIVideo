import os
import boto3
from botocore.exceptions import NoCredentialsError

def upload_to_s3(file_path: str, bucket: str, object_name: str = None) -> str:
    """
    Upload a file to an S3 bucket
    """
    if object_name is None:
        object_name = os.path.basename(file_path)

    s3_client = boto3.client('s3')
    
    try:
        s3_client.upload_file(file_path, bucket, object_name)
        url = f"https://{bucket}.s3.amazonaws.com/{object_name}"
        return url
    except NoCredentialsError:
        print("Error: No AWS credentials found.")
        return None
