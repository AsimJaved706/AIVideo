import base64
from io import BytesIO
from google import genai
try:
    from PIL import Image, ImageDraw
except ImportError:
    pass


def _build_image_prompt(
    prompt: str,
    style: str,
    lighting_style: str,
    color_palette: str,
    framing: str,
) -> str:
    core_prompt = " ".join((prompt or "").split()).strip()
    return (
        "Create a photorealistic vertical 9:16 frame for short-form video. "
        f"Style: {style}. Lighting: {lighting_style}. Color palette: {color_palette}. Framing: {framing}. "
        "Real people, natural skin texture, real-world environment, believable depth, detailed clothing and objects, "
        "clean composition, cinematic but natural look, no text, no logo, no watermark, no graphic overlays. "
        f"Scene: {core_prompt}"
    )


def _generate_placeholder_image_data_uri(
    prompt: str,
    style: str,
    lighting_style: str,
    color_palette: str,
    framing: str,
) -> str:
    print(f"Falling back to local placeholder seed for: '{prompt}' in '{style}' style")

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
    return f"data:image/jpeg;base64,{b64_img}"

def generate_stock_image_data_uri(
    prompt: str,
    style: str = "photorealistic",
    lighting_style: str = "natural_daylight",
    color_palette: str = "neutral",
    framing: str = "medium_shot",
    api_key: str = None,
) -> str:
    """
    Generates a realistic 768x1280 seed image using Gemini Imagen.
    Falls back to a local placeholder if generation fails.
    """
    final_prompt = _build_image_prompt(prompt, style, lighting_style, color_palette, framing)

    if api_key:
        try:
            print(f"Generating Imagen seed for: '{prompt}'")
            client = genai.Client(api_key=api_key)
            response = client.models.generate_images(
                model='imagen-3.0-generate-001',
                prompt=final_prompt,
                config={
                    "number_of_images": 1,
                    "aspect_ratio": "9:16",
                    "output_mime_type": "image/jpeg",
                },
            )

            generated = getattr(response, "generated_images", []) or []
            if generated:
                image_obj = generated[0].image
                if hasattr(image_obj, "image_bytes") and image_obj.image_bytes:
                    encoded = base64.b64encode(image_obj.image_bytes).decode("utf-8")
                    print("Imagen seed image generated successfully.")
                    return f"data:image/jpeg;base64,{encoded}"

            print("Imagen returned no image bytes; using placeholder fallback.")
        except Exception as e:
            print(f"Imagen generation failed ({e}); using placeholder fallback.")

    return _generate_placeholder_image_data_uri(
        prompt=prompt,
        style=style,
        lighting_style=lighting_style,
        color_palette=color_palette,
        framing=framing,
    )
