import os
from moviepy import AudioFileClip, ColorClip, ImageClip, VideoFileClip, concatenate_audioclips, concatenate_videoclips


def _build_fallback_visual_clip(image_path: str, duration: float):
    safe_duration = max(float(duration or 0), 0.1)

    if image_path and os.path.exists(image_path):
        return ImageClip(image_path).resized(height=1280).with_duration(safe_duration)

    return ColorClip(size=(720, 1280), color=(20, 24, 35)).with_duration(safe_duration)


def render_final_video(clips_paths: list, narration_paths: list, output_path: str, fallback_image_paths: list | None = None):
    """
    Renders the final video using MoviePy by stitching clips and setting audio.
    Falls back to still images when video clips are missing.
    """
    if not narration_paths:
        raise ValueError("Missing audio paths")
        
    # Process audio
    audio_clips = [AudioFileClip(a) for a in narration_paths]
    final_audio = concatenate_audioclips(audio_clips) if len(audio_clips) > 1 else audio_clips[0]
    
    # Process video, aligning scene visuals to narration order.
    fallback_image_paths = fallback_image_paths or []
    loaded_clips = []
    for index, audio_clip in enumerate(audio_clips):
        video_path = clips_paths[index] if index < len(clips_paths) else None
        image_path = fallback_image_paths[index] if index < len(fallback_image_paths) else None

        if video_path and os.path.exists(video_path):
            visual_clip = VideoFileClip(video_path)
            if visual_clip.duration > audio_clip.duration:
                visual_clip = visual_clip.subclipped(0, audio_clip.duration)
            elif visual_clip.duration < audio_clip.duration:
                visual_clip = visual_clip.loop(duration=audio_clip.duration)
            loaded_clips.append(visual_clip)
            continue

        loaded_clips.append(_build_fallback_visual_clip(image_path, audio_clip.duration))
            
    if not loaded_clips:
        raise ValueError("No visual clips or fallback images were available to render.")
        
    # Compose
    final_video = concatenate_videoclips(loaded_clips, method="compose")
    
    # Trim to match audio
    if final_video.duration > final_audio.duration:
        final_video = final_video.subclipped(0, final_audio.duration)
        
    final_video = final_video.with_audio(final_audio)
    
    # Export
    final_video.write_videofile(output_path, fps=24, codec="libx264", audio_codec="aac")
    
    # Memory cleanup
    final_video.close()
    for c in loaded_clips:
        c.close()
    for a in audio_clips:
        a.close()
        
    return output_path
