from moviepy import VideoFileClip, AudioFileClip, concatenate_videoclips, concatenate_audioclips

def render_final_video(clips_paths: list, narration_paths: list, output_path: str):
    """
    Renders the final video using MoviePy by stitching clips and setting audio.
    """
    if not clips_paths or not narration_paths:
        raise ValueError("Missing clips or audio paths")
        
    # Process audio
    audio_clips = [AudioFileClip(a) for a in narration_paths]
    final_audio = concatenate_audioclips(audio_clips) if len(audio_clips) > 1 else audio_clips[0]
    
    # Process video
    import os
    loaded_clips = []
    for p in clips_paths:
        if os.path.exists(p):
            vc = VideoFileClip(p)
            loaded_clips.append(vc)
            
    if not loaded_clips:
        raise ValueError("No visual clips were successfully generated to render.")
        
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
