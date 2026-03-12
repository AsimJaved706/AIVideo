import base64
from io import BytesIO
try:
    from PIL import Image, ImageDraw
except ImportError:
    pass

def generate_stock_image_data_uri(
    prompt: str,
    style: str = "photorealistic",
    lighting_style: str = "natural_daylight",
    color_palette: str = "neutral",
    framing: str = "medium_shot",
    api_key: str = None,
) -> str:
    """
    Generates a styled placeholder 768x1280 image natively in Python to act as the 
    seed for RunwayML Gen-3 Alpha Turbo (which requires an image), 
    bypassing 3rd-party API dependencies.
    """
    print(f"Generating local placeholder seed for: '{prompt}' in '{style}' style")

    palette = {
        "photorealistic": (28, 28, 28),
        "lifestyle": (62, 78, 94),
        "studio": (18, 18, 24),
        "dramatic": (10, 10, 10),
    }
    base_color = palette.get(style, (28, 28, 28))

    img = Image.new('RGB', (768, 1280), color=base_color)
    draw = ImageDraw.Draw(img)
    draw.rounded_rectangle((44, 64, 724, 1216), radius=40, outline=(220, 220, 220), width=3)
    draw.text((70, 100), f"STYLE: {style.upper()}", fill=(255, 255, 255))
    draw.text((70, 150), f"LIGHT: {lighting_style} | COLOR: {color_palette}", fill=(220, 220, 220))
    draw.text((70, 200), f"FRAMING: {framing}", fill=(220, 220, 220))

    trimmed_prompt = (prompt or "").strip()[:180]
    y = 260
    line_length = 28
    for idx in range(0, len(trimmed_prompt), line_length):
        draw.text((70, y), trimmed_prompt[idx:idx + line_length], fill=(245, 245, 245))
        y += 44
    
    buffered = BytesIO()
    img.save(buffered, format="JPEG", quality=90)
    b64_img = base64.b64encode(buffered.getvalue()).decode('utf-8')
    
    print("Local seed image generated successfully.")
    return f"data:image/jpeg;base64,{b64_img}"
