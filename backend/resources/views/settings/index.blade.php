<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg md:text-2xl font-black text-slate-900 leading-tight">
            {{ __('Settings & Integrations') }}
        </h2>
    </x-slot>

    <div class="space-y-6 md:space-y-8">
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-3 md:p-4 flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium text-sm md:text-base">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-2xl md:rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-4 md:p-6 border-b border-slate-100 bg-slate-50/50 flex items-start md:items-center gap-3 md:gap-4">
                <div class="h-10 md:h-12 w-10 md:w-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 md:w-6 h-5 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base md:text-lg font-bold text-slate-900">API Credentials</h3>
                    <p class="text-xs md:text-sm text-slate-500 mt-0.5 md:mt-1">Configure your third-party generation services. Keys are encrypted at rest.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('settings.store') }}" class="p-4 md:p-6 space-y-6 md:space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    <!-- Google Gemini -->
                    <div class="bg-slate-50 rounded-lg md:rounded-xl p-4 md:p-5 border border-slate-100">
                        <label class="block text-xs md:text-sm font-bold text-slate-700 mb-2">Google Gemini API Key</label>
                        <div class="relative">
                            <input type="password" id="gemini" name="gemini_api_key"
                                class="w-full rounded-lg border border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white pr-10 text-sm"
                                placeholder="AIzaSy..." value="{{ $user->gemini_api_key }}">
                            <button type="button" onclick="toggleVisibility('gemini')"
                                class="absolute inset-y-0 right-0 pr-2 md:pr-3 flex items-center text-slate-400 hover:text-indigo-600">
                                <svg class="h-4 md:h-5 w-4 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-slate-500 mt-2">Required for script and prompt generation.</p>
                    </div>

                    <!-- ElevenLabs -->
                    <div class="bg-slate-50 rounded-lg md:rounded-xl p-4 md:p-5 border border-slate-100">
                        <label class="block text-xs md:text-sm font-bold text-slate-700 mb-2">ElevenLabs API Key</label>
                        <div class="relative">
                            <input type="password" id="elevenlabs" name="elevenlabs_api_key"
                                class="w-full rounded-lg border border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white pr-10 text-sm"
                                placeholder="sk_..." value="{{ $user->elevenlabs_api_key }}">
                            <button type="button" onclick="toggleVisibility('elevenlabs')"
                                class="absolute inset-y-0 right-0 pr-2 md:pr-3 flex items-center text-slate-400 hover:text-indigo-600">
                                <svg class="h-4 md:h-5 w-4 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-slate-500 mt-2">Required for cinematic voiceovers.</p>
                    </div>

                    <!-- RunwayML -->
                    <div class="bg-slate-50 rounded-lg md:rounded-xl p-4 md:p-5 border border-slate-100 md:col-span-2">
                        <label class="block text-xs md:text-sm font-bold text-slate-700 mb-2">RunwayML API Key</label>
                        <div class="relative">
                            <input type="password" id="runwayml" name="runwayml_api_key"
                                class="w-full rounded-lg border border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white pr-10 text-sm"
                                placeholder="key_..." value="{{ $user->runwayml_api_key }}">
                            <button type="button" onclick="toggleVisibility('runwayml')"
                                class="absolute inset-y-0 right-0 pr-2 md:pr-3 flex items-center text-slate-400 hover:text-indigo-600">
                                <svg class="h-4 md:h-5 w-4 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-slate-500 mt-2">Required for Runway Gen-3 clip generation.</p>
                    </div>
                </div>

                <div class="pt-4 md:pt-6 border-t border-slate-100">
                    <h4 class="text-sm md:text-base font-bold text-slate-900 mb-4 md:mb-6">Voice Settings</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                        <div class="bg-slate-50 rounded-lg md:rounded-xl p-4 md:p-5 border border-slate-100 md:col-span-2 lg:col-span-3">
                            <label class="block text-xs md:text-sm font-bold text-slate-700 mb-2">ElevenLabs Voice ID</label>
                            <input type="text" name="voice_id"
                                class="w-full rounded-lg border border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white text-sm"
                                placeholder="21m00Tcm4TlvDq8ikWAM"
                                value="{{ old('voice_id', $user->voice_id ?? '21m00Tcm4TlvDq8ikWAM') }}">
                            <p class="text-xs text-slate-500 mt-2">Use any ElevenLabs voice ID. Leave the default if you are unsure.</p>
                        </div>

                        <div class="bg-slate-50 rounded-lg md:rounded-xl p-4 md:p-5 border border-slate-100">
                            <label class="block text-xs md:text-sm font-bold text-slate-700 mb-2">Voice Model</label>
                            <select name="voice_model" class="w-full rounded-lg border border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white p-2.5 text-sm">
                                <option value="eleven_multilingual_v2" {{ old('voice_model', $user->voice_model ?? 'eleven_multilingual_v2') === 'eleven_multilingual_v2' ? 'selected' : '' }}>Eleven Multilingual v2</option>
                                <option value="eleven_turbo_v2_5" {{ old('voice_model', $user->voice_model ?? 'eleven_multilingual_v2') === 'eleven_turbo_v2_5' ? 'selected' : '' }}>Eleven Turbo v2.5</option>
                            </select>
                            <p class="text-xs text-slate-500 mt-2">Higher quality vs faster narration.</p>
                        </div>

                        <div class="bg-slate-50 rounded-lg md:rounded-xl p-4 md:p-5 border border-slate-100">
                            <label class="block text-xs md:text-sm font-bold text-slate-700 mb-2">Audio Format</label>
                            <select name="voice_output_format" class="w-full rounded-lg border border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white p-2.5 text-sm">
                                <option value="mp3_44100_128" {{ old('voice_output_format', $user->voice_output_format ?? 'mp3_44100_128') === 'mp3_44100_128' ? 'selected' : '' }}>MP3 44.1kHz / 128 Kbps</option>
                                <option value="mp3_44100_192" {{ old('voice_output_format', $user->voice_output_format ?? 'mp3_44100_128') === 'mp3_44100_192' ? 'selected' : '' }}>MP3 44.1kHz / 192 Kbps</option>
                            </select>
                            <p class="text-xs text-slate-500 mt-2">Higher bitrate = cleaner.</p>
                        </div>

                        <div class="bg-slate-50 rounded-lg md:rounded-xl p-4 md:p-5 border border-slate-100 md:col-span-2 lg:col-span-3">
                            <label class="block text-xs md:text-sm font-bold text-slate-700 mb-2">Fallback Language</label>
                            <select name="fallback_language" class="w-full rounded-lg border border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white p-2.5 text-sm">
                                <option value="en" {{ old('fallback_language', $user->fallback_language ?? 'en') === 'en' ? 'selected' : '' }}>English</option>
                                <option value="es" {{ old('fallback_language', $user->fallback_language ?? 'en') === 'es' ? 'selected' : '' }}>Spanish</option>
                                <option value="fr" {{ old('fallback_language', $user->fallback_language ?? 'en') === 'fr' ? 'selected' : '' }}>French</option>
                                <option value="de" {{ old('fallback_language', $user->fallback_language ?? 'en') === 'de' ? 'selected' : '' }}>German</option>
                                <option value="hi" {{ old('fallback_language', $user->fallback_language ?? 'en') === 'hi' ? 'selected' : '' }}>Hindi</option>
                            </select>
                            <p class="text-xs text-slate-500 mt-2">Fallback when ElevenLabs fails.</p>
                        </div>

                        <div class="bg-slate-50 rounded-lg md:rounded-xl p-4 md:p-5 border border-slate-100">
                            <label class="block text-xs md:text-sm font-bold text-slate-700 mb-2">Stability</label>
                            <input type="number" name="voice_stability" min="0" max="1" step="0.05" class="w-full rounded-lg border border-slate-300 shadow-sm text-sm"
                                value="{{ old('voice_stability', $user->voice_stability ?? 0.35) }}">
                            <p class="text-xs text-slate-500 mt-2">0.30–0.45 recommended.</p>
                        </div>

                        <div class="bg-slate-50 rounded-lg md:rounded-xl p-4 md:p-5 border border-slate-100">
                            <label class="block text-xs md:text-sm font-bold text-slate-700 mb-2">Similarity Boost</label>
                            <input type="number" name="voice_similarity_boost" min="0" max="1" step="0.05" class="w-full rounded-lg border border-slate-300 shadow-sm text-sm"
                                value="{{ old('voice_similarity_boost', $user->voice_similarity_boost ?? 0.80) }}">
                            <p class="text-xs text-slate-500 mt-2">Keep voice identity.</p>
                        </div>

                        <div class="bg-slate-50 rounded-lg md:rounded-xl p-4 md:p-5 border border-slate-100">
                            <label class="block text-xs md:text-sm font-bold text-slate-700 mb-2">Voice Style</label>
                            <input type="number" name="voice_style" min="0" max="1" step="0.05" class="w-full rounded-lg border border-slate-300 shadow-sm text-sm"
                                value="{{ old('voice_style', $user->voice_style ?? 0.15) }}">
                            <p class="text-xs text-slate-500 mt-2">Keep natural.</p>
                        </div>

                        <div class="bg-slate-50 rounded-lg md:rounded-xl p-4 md:p-5 border border-slate-100 flex items-start md:items-center justify-between gap-4">
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-slate-700 mb-1 md:mb-2">Speaker Boost</label>
                                <p class="text-xs text-slate-500">Improves clarity.</p>
                            </div>
                            <div class="pt-1">
                                <input type="hidden" name="voice_speaker_boost" value="0">
                                <input type="checkbox" name="voice_speaker_boost" value="1" class="rounded border-slate-300 text-indigo-600 shadow-sm"
                                    {{ old('voice_speaker_boost', $user->voice_speaker_boost ?? true) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 md:pt-6 border-t border-slate-100">
                    <h4 class="text-sm md:text-base font-bold text-slate-900 mb-4 md:mb-6">Visual & Image Settings</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        <div class="bg-slate-50 rounded-lg md:rounded-xl p-4 md:p-5 border border-slate-100">
                            <label class="block text-xs md:text-sm font-bold text-slate-700 mb-2">Script Tone</label>
                            <select name="script_tone" class="w-full rounded-lg border border-slate-300 text-sm p-2.5">
                                <option value="natural" {{ old('script_tone', $user->script_tone ?? 'natural') === 'natural' ? 'selected' : '' }}>Natural</option>
                                <option value="educational" {{ old('script_tone', $user->script_tone ?? 'natural') === 'educational' ? 'selected' : '' }}>Educational</option>
                                <option value="storytelling" {{ old('script_tone', $user->script_tone ?? 'natural') === 'storytelling' ? 'selected' : '' }}>Storytelling</option>
                                <option value="persuasive" {{ old('script_tone', $user->script_tone ?? 'natural') === 'persuasive' ? 'selected' : '' }}>Persuasive</option>
                            </select>
                        </div>

                        <div class="bg-slate-50 rounded-lg md:rounded-xl p-4 md:p-5 border border-slate-100">
                            <label class="block text-xs md:text-sm font-bold text-slate-700 mb-2">Runway Visual Style</label>
                            <select name="visual_style" class="w-full rounded-lg border border-slate-300 text-sm p-2.5">
                                <option value="documentary" {{ old('visual_style', $user->visual_style ?? 'documentary') === 'documentary' ? 'selected' : '' }}>Documentary</option>
                                <option value="cinematic" {{ old('visual_style', $user->visual_style ?? 'documentary') === 'cinematic' ? 'selected' : '' }}>Cinematic</option>
                                <option value="ugc" {{ old('visual_style', $user->visual_style ?? 'documentary') === 'ugc' ? 'selected' : '' }}>UGC / Phone</option>
                                <option value="commercial" {{ old('visual_style', $user->visual_style ?? 'documentary') === 'commercial' ? 'selected' : '' }}>Commercial</option>
                            </select>
                        </div>

                        <div class="bg-slate-50 rounded-lg md:rounded-xl p-4 md:p-5 border border-slate-100">
                            <label class="block text-xs md:text-sm font-bold text-slate-700 mb-2">Seed Image Style</label>
                            <select name="seed_image_style" class="w-full rounded-lg border border-slate-300 text-sm p-2.5">
                                <option value="photorealistic" {{ old('seed_image_style', $user->seed_image_style ?? 'photorealistic') === 'photorealistic' ? 'selected' : '' }}>Photorealistic</option>
                                <option value="lifestyle" {{ old('seed_image_style', $user->seed_image_style ?? 'photorealistic') === 'lifestyle' ? 'selected' : '' }}>Lifestyle</option>
                                <option value="studio" {{ old('seed_image_style', $user->seed_image_style ?? 'photorealistic') === 'studio' ? 'selected' : '' }}>Studio</option>
                                <option value="dramatic" {{ old('seed_image_style', $user->seed_image_style ?? 'photorealistic') === 'dramatic' ? 'selected' : '' }}>Dramatic</option>
                            </select>
                        </div>

                        <div class="bg-slate-50 rounded-lg md:rounded-xl p-4 md:p-5 border border-slate-100">
                            <label class="block text-xs md:text-sm font-bold text-slate-700 mb-2">Lighting Style</label>
                            <select name="image_lighting_style" class="w-full rounded-lg border border-slate-300 text-sm p-2.5">
                                <option value="natural_daylight" {{ old('image_lighting_style', $user->image_lighting_style ?? 'natural_daylight') === 'natural_daylight' ? 'selected' : '' }}>Natural Daylight</option>
                                <option value="soft_studio" {{ old('image_lighting_style', $user->image_lighting_style ?? 'natural_daylight') === 'soft_studio' ? 'selected' : '' }}>Soft Studio</option>
                                <option value="golden_hour" {{ old('image_lighting_style', $user->image_lighting_style ?? 'natural_daylight') === 'golden_hour' ? 'selected' : '' }}>Golden Hour</option>
                                <option value="moody" {{ old('image_lighting_style', $user->image_lighting_style ?? 'natural_daylight') === 'moody' ? 'selected' : '' }}>Moody</option>
                            </select>
                        </div>

                        <div class="bg-slate-50 rounded-lg md:rounded-xl p-4 md:p-5 border border-slate-100">
                            <label class="block text-xs md:text-sm font-bold text-slate-700 mb-2">Color Palette</label>
                            <select name="image_color_palette" class="w-full rounded-lg border border-slate-300 text-sm p-2.5">
                                <option value="neutral" {{ old('image_color_palette', $user->image_color_palette ?? 'neutral') === 'neutral' ? 'selected' : '' }}>Neutral</option>
                                <option value="warm" {{ old('image_color_palette', $user->image_color_palette ?? 'neutral') === 'warm' ? 'selected' : '' }}>Warm</option>
                                <option value="cool" {{ old('image_color_palette', $user->image_color_palette ?? 'neutral') === 'cool' ? 'selected' : '' }}>Cool</option>
                                <option value="high_contrast" {{ old('image_color_palette', $user->image_color_palette ?? 'neutral') === 'high_contrast' ? 'selected' : '' }}>High Contrast</option>
                            </select>
                        </div>

                        <div class="bg-slate-50 rounded-lg md:rounded-xl p-4 md:p-5 border border-slate-100">
                            <label class="block text-xs md:text-sm font-bold text-slate-700 mb-2">Image Framing</label>
                            <select name="image_framing" class="w-full rounded-lg border border-slate-300 text-sm p-2.5">
                                <option value="close_up" {{ old('image_framing', $user->image_framing ?? 'medium_shot') === 'close_up' ? 'selected' : '' }}>Close Up</option>
                                <option value="medium_shot" {{ old('image_framing', $user->image_framing ?? 'medium_shot') === 'medium_shot' ? 'selected' : '' }}>Medium Shot</option>
                                <option value="wide_shot" {{ old('image_framing', $user->image_framing ?? 'medium_shot') === 'wide_shot' ? 'selected' : '' }}>Wide Shot</option>
                            </select>
                        </div>

                        <div class="bg-slate-50 rounded-lg md:rounded-xl p-4 md:p-5 border border-slate-100 md:col-span-2">
                            <label class="block text-xs md:text-sm font-bold text-slate-700 mb-2">Negative Prompt</label>
                            <textarea name="image_negative_prompt" rows="2" class="w-full rounded-lg border border-slate-300 shadow-sm text-sm" placeholder="cartoon, plastic skin, bad hands...">{{ old('image_negative_prompt', $user->image_negative_prompt ?? '') }}</textarea>
                            <p class="text-xs text-slate-500 mt-2">What to avoid in visuals.</p>
                        </div>

                        <div class="bg-slate-50 rounded-lg md:rounded-xl p-4 md:p-5 border border-slate-100">
                            <label class="block text-xs md:text-sm font-bold text-slate-700 mb-2">Camera Motion</label>
                            <select name="camera_motion" class="w-full rounded-lg border border-slate-300 text-sm p-2.5">
                                <option value="gentle_handheld" {{ old('camera_motion', $user->camera_motion ?? 'gentle_handheld') === 'gentle_handheld' ? 'selected' : '' }}>Gentle Handheld</option>
                                <option value="slow_push_in" {{ old('camera_motion', $user->camera_motion ?? 'gentle_handheld') === 'slow_push_in' ? 'selected' : '' }}>Slow Push In</option>
                                <option value="smooth_pan" {{ old('camera_motion', $user->camera_motion ?? 'gentle_handheld') === 'smooth_pan' ? 'selected' : '' }}>Smooth Pan</option>
                                <option value="static" {{ old('camera_motion', $user->camera_motion ?? 'gentle_handheld') === 'static' ? 'selected' : '' }}>Static</option>
                            </select>
                        </div>

                        <div class="bg-slate-50 rounded-lg md:rounded-xl p-4 md:p-5 border border-slate-100">
                            <label class="block text-xs md:text-sm font-bold text-slate-700 mb-2">Runway Duration</label>
                            <select name="runway_duration" class="w-full rounded-lg border border-slate-300 text-sm p-2.5">
                                <option value="5" {{ old('runway_duration', $user->runway_duration ?? 5) === 5 || old('runway_duration', $user->runway_duration ?? 5) === '5' ? 'selected' : '' }}>5 seconds</option>
                                <option value="10" {{ old('runway_duration', $user->runway_duration ?? 5) === 10 || old('runway_duration', $user->runway_duration ?? 5) === '10' ? 'selected' : '' }}>10 seconds</option>
                            </select>
                        </div>

                        <div class="bg-slate-50 rounded-lg md:rounded-xl p-4 md:p-5 border border-slate-100">
                            <label class="block text-xs md:text-sm font-bold text-slate-700 mb-2">Storage Preference</label>
                            <select name="storage_preference" class="w-full rounded-lg border border-slate-300 text-sm p-2.5">
                                <option value="local" {{ old('storage_preference', $user->storage_preference ?? 'local') === 'local' ? 'selected' : '' }}>Local Storage</option>
                                <option value="s3" {{ old('storage_preference', $user->storage_preference ?? 'local') === 's3' ? 'selected' : '' }}>AWS S3</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center pt-6 md:pt-8 border-t border-slate-100">
                    <button type="submit" class="rounded-full bg-gradient-to-r from-indigo-500 to-fuchsia-500 px-6 md:px-8 py-3 md:py-4 text-sm md:text-base font-bold text-white shadow-glow transition hover:scale-105 disabled:opacity-50">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleVisibility(id) {
            const field = document.getElementById(id);
            field.type = field.type === 'password' ? 'text' : 'password';
        }
    </script>
</x-app-layout>
            {{ __('System Settings & Integrations') }}
        </h2>
    </x-slot>

    <div class="space-y-8">
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center gap-4">
                <div class="h-12 w-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">API Credentials</h3>
                    <p class="text-sm text-gray-500 mt-1">Configure your third-party generation services. Keys are
                        encrypted at rest.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('settings.store') }}" class="p-6 space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Google Gemini -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Google Gemini API Key</label>
                        <div class="relative">
                            <input type="password" id="gemini" name="gemini_api_key"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white pr-10"
                                placeholder="AIzaSy..." value="{{ $user->gemini_api_key }}">
                            <button type="button" onclick="toggleVisibility('gemini')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-indigo-600">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Required for script and prompt generation.</p>
                    </div>

                    <!-- ElevenLabs -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <label class="block text-sm font-bold text-gray-700 mb-2">ElevenLabs API Key</label>
                        <div class="relative">
                            <input type="password" id="elevenlabs" name="elevenlabs_api_key"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white pr-10"
                                placeholder="sk_..." value="{{ $user->elevenlabs_api_key }}">
                            <button type="button" onclick="toggleVisibility('elevenlabs')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-indigo-600">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Required for cinematic voiceovers.</p>
                    </div>

                    <!-- RunwayML -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <label class="block text-sm font-bold text-gray-700 mb-2">RunwayML API Key</label>
                        <div class="relative">
                            <input type="password" id="runwayml" name="runwayml_api_key"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white pr-10"
                                placeholder="key_..." value="{{ $user->runwayml_api_key }}">
                            <button type="button" onclick="toggleVisibility('runwayml')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-indigo-600">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Required for Runway Gen-3 clip generation.</p>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100">
                    <h4 class="text-md font-bold text-gray-900 mb-4">Voice Settings</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">ElevenLabs Voice ID</label>
                            <input type="text" name="voice_id"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white"
                                placeholder="21m00Tcm4TlvDq8ikWAM"
                                value="{{ old('voice_id', $user->voice_id ?? '21m00Tcm4TlvDq8ikWAM') }}">
                            <p class="text-xs text-gray-500 mt-2">Use any ElevenLabs voice ID. Leave the default if you are unsure.</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Voice Model</label>
                            <select name="voice_model" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white p-2.5">
                                <option value="eleven_multilingual_v2" {{ old('voice_model', $user->voice_model ?? 'eleven_multilingual_v2') === 'eleven_multilingual_v2' ? 'selected' : '' }}>Eleven Multilingual v2</option>
                                <option value="eleven_turbo_v2_5" {{ old('voice_model', $user->voice_model ?? 'eleven_multilingual_v2') === 'eleven_turbo_v2_5' ? 'selected' : '' }}>Eleven Turbo v2.5</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-2">Choose between higher quality and faster narration.</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Audio Output Format</label>
                            <select name="voice_output_format" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white p-2.5">
                                <option value="mp3_44100_128" {{ old('voice_output_format', $user->voice_output_format ?? 'mp3_44100_128') === 'mp3_44100_128' ? 'selected' : '' }}>MP3 44.1kHz / 128 kbps</option>
                                <option value="mp3_44100_192" {{ old('voice_output_format', $user->voice_output_format ?? 'mp3_44100_128') === 'mp3_44100_192' ? 'selected' : '' }}>MP3 44.1kHz / 192 kbps</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-2">Higher bitrate gives cleaner exported narration.</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Fallback Language</label>
                            <select name="fallback_language" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white p-2.5">
                                <option value="en" {{ old('fallback_language', $user->fallback_language ?? 'en') === 'en' ? 'selected' : '' }}>English</option>
                                <option value="es" {{ old('fallback_language', $user->fallback_language ?? 'en') === 'es' ? 'selected' : '' }}>Spanish</option>
                                <option value="fr" {{ old('fallback_language', $user->fallback_language ?? 'en') === 'fr' ? 'selected' : '' }}>French</option>
                                <option value="de" {{ old('fallback_language', $user->fallback_language ?? 'en') === 'de' ? 'selected' : '' }}>German</option>
                                <option value="hi" {{ old('fallback_language', $user->fallback_language ?? 'en') === 'hi' ? 'selected' : '' }}>Hindi</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-2">Used only if narration falls back to gTTS.</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Voice Stability</label>
                            <input type="number" name="voice_stability" min="0" max="1" step="0.05"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white"
                                value="{{ old('voice_stability', $user->voice_stability ?? 0.35) }}">
                            <p class="text-xs text-gray-500 mt-2">Lower values sound more expressive. Recommended: 0.30–0.45.</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Similarity Boost</label>
                            <input type="number" name="voice_similarity_boost" min="0" max="1" step="0.05"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white"
                                value="{{ old('voice_similarity_boost', $user->voice_similarity_boost ?? 0.80) }}">
                            <p class="text-xs text-gray-500 mt-2">Higher values keep the chosen voice identity stronger.</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Voice Style</label>
                            <input type="number" name="voice_style" min="0" max="1" step="0.05"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white"
                                value="{{ old('voice_style', $user->voice_style ?? 0.15) }}">
                            <p class="text-xs text-gray-500 mt-2">Adds controlled flair. Keep low for a natural voice.</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 flex items-start justify-between gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Speaker Boost</label>
                                <p class="text-xs text-gray-500">Improves clarity and presence for narration.</p>
                            </div>
                            <div class="pt-1">
                                <input type="hidden" name="voice_speaker_boost" value="0">
                                <input type="checkbox" name="voice_speaker_boost" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                    {{ old('voice_speaker_boost', $user->voice_speaker_boost ?? true) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100">
                    <h4 class="text-md font-bold text-gray-900 mb-4">Script & Visual Style</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Script Tone</label>
                            <select name="script_tone" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white p-2.5">
                                <option value="natural" {{ old('script_tone', $user->script_tone ?? 'natural') === 'natural' ? 'selected' : '' }}>Natural</option>
                                <option value="educational" {{ old('script_tone', $user->script_tone ?? 'natural') === 'educational' ? 'selected' : '' }}>Educational</option>
                                <option value="storytelling" {{ old('script_tone', $user->script_tone ?? 'natural') === 'storytelling' ? 'selected' : '' }}>Storytelling</option>
                                <option value="persuasive" {{ old('script_tone', $user->script_tone ?? 'natural') === 'persuasive' ? 'selected' : '' }}>Persuasive</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-2">Controls how the script sounds when spoken.</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Runway Visual Style</label>
                            <select name="visual_style" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white p-2.5">
                                <option value="documentary" {{ old('visual_style', $user->visual_style ?? 'documentary') === 'documentary' ? 'selected' : '' }}>Documentary</option>
                                <option value="cinematic" {{ old('visual_style', $user->visual_style ?? 'documentary') === 'cinematic' ? 'selected' : '' }}>Cinematic</option>
                                <option value="ugc" {{ old('visual_style', $user->visual_style ?? 'documentary') === 'ugc' ? 'selected' : '' }}>UGC / Phone Camera</option>
                                <option value="commercial" {{ old('visual_style', $user->visual_style ?? 'documentary') === 'commercial' ? 'selected' : '' }}>Commercial</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-2">Affects the motion and look of generated clips.</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Seed Image Style</label>
                            <select name="seed_image_style" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white p-2.5">
                                <option value="photorealistic" {{ old('seed_image_style', $user->seed_image_style ?? 'photorealistic') === 'photorealistic' ? 'selected' : '' }}>Photorealistic</option>
                                <option value="lifestyle" {{ old('seed_image_style', $user->seed_image_style ?? 'photorealistic') === 'lifestyle' ? 'selected' : '' }}>Lifestyle</option>
                                <option value="studio" {{ old('seed_image_style', $user->seed_image_style ?? 'photorealistic') === 'studio' ? 'selected' : '' }}>Studio</option>
                                <option value="dramatic" {{ old('seed_image_style', $user->seed_image_style ?? 'photorealistic') === 'dramatic' ? 'selected' : '' }}>Dramatic</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-2">Shapes the seed image prompt used before Runway animation.</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Lighting Style</label>
                            <select name="image_lighting_style" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white p-2.5">
                                <option value="natural_daylight" {{ old('image_lighting_style', $user->image_lighting_style ?? 'natural_daylight') === 'natural_daylight' ? 'selected' : '' }}>Natural Daylight</option>
                                <option value="soft_studio" {{ old('image_lighting_style', $user->image_lighting_style ?? 'natural_daylight') === 'soft_studio' ? 'selected' : '' }}>Soft Studio</option>
                                <option value="golden_hour" {{ old('image_lighting_style', $user->image_lighting_style ?? 'natural_daylight') === 'golden_hour' ? 'selected' : '' }}>Golden Hour</option>
                                <option value="moody" {{ old('image_lighting_style', $user->image_lighting_style ?? 'natural_daylight') === 'moody' ? 'selected' : '' }}>Moody</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-2">Changes the light mood used in the visual prompt.</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Color Palette</label>
                            <select name="image_color_palette" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white p-2.5">
                                <option value="neutral" {{ old('image_color_palette', $user->image_color_palette ?? 'neutral') === 'neutral' ? 'selected' : '' }}>Neutral</option>
                                <option value="warm" {{ old('image_color_palette', $user->image_color_palette ?? 'neutral') === 'warm' ? 'selected' : '' }}>Warm</option>
                                <option value="cool" {{ old('image_color_palette', $user->image_color_palette ?? 'neutral') === 'cool' ? 'selected' : '' }}>Cool</option>
                                <option value="high_contrast" {{ old('image_color_palette', $user->image_color_palette ?? 'neutral') === 'high_contrast' ? 'selected' : '' }}>High Contrast</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-2">Helps steer the final image mood and scene grading.</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Image Framing</label>
                            <select name="image_framing" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white p-2.5">
                                <option value="close_up" {{ old('image_framing', $user->image_framing ?? 'medium_shot') === 'close_up' ? 'selected' : '' }}>Close Up</option>
                                <option value="medium_shot" {{ old('image_framing', $user->image_framing ?? 'medium_shot') === 'medium_shot' ? 'selected' : '' }}>Medium Shot</option>
                                <option value="wide_shot" {{ old('image_framing', $user->image_framing ?? 'medium_shot') === 'wide_shot' ? 'selected' : '' }}>Wide Shot</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-2">Controls how tightly the subject is framed.</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Negative Prompt</label>
                            <textarea name="image_negative_prompt" rows="3"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white"
                                placeholder="cartoon, plastic skin, bad hands, extra limbs, blur, oversaturated">{{ old('image_negative_prompt', $user->image_negative_prompt ?? '') }}</textarea>
                            <p class="text-xs text-gray-500 mt-2">Tell the generator what to avoid in scenes.</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Camera Motion</label>
                            <select name="camera_motion" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white p-2.5">
                                <option value="gentle_handheld" {{ old('camera_motion', $user->camera_motion ?? 'gentle_handheld') === 'gentle_handheld' ? 'selected' : '' }}>Gentle Handheld</option>
                                <option value="slow_push_in" {{ old('camera_motion', $user->camera_motion ?? 'gentle_handheld') === 'slow_push_in' ? 'selected' : '' }}>Slow Push In</option>
                                <option value="smooth_pan" {{ old('camera_motion', $user->camera_motion ?? 'gentle_handheld') === 'smooth_pan' ? 'selected' : '' }}>Smooth Pan</option>
                                <option value="static" {{ old('camera_motion', $user->camera_motion ?? 'gentle_handheld') === 'static' ? 'selected' : '' }}>Mostly Static</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-2">Helps keep motion consistent across scenes.</p>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100">
                    <h4 class="text-md font-bold text-gray-900 mb-4">Clip Settings</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Runway Clip Duration</label>
                            <select name="runway_duration" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white p-2.5">
                                <option value="5" {{ (string) old('runway_duration', $user->runway_duration ?? 5) === '5' ? 'selected' : '' }}>5 seconds</option>
                                <option value="10" {{ (string) old('runway_duration', $user->runway_duration ?? 5) === '10' ? 'selected' : '' }}>10 seconds</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-2">Longer clips can look smoother but take more time and credits.</p>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100">
                    <h4 class="text-md font-bold text-gray-900 mb-4">Storage Preferences</h4>
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Media Asset Storage Destination</label>
                        <select name="storage_preference" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white p-2.5">
                            <option value="local" {{ $user->storage_preference === 'local' ? 'selected' : '' }}>Local Generation (Recommended for dev)</option>
                            <option value="s3" {{ $user->storage_preference === 's3' ? 'selected' : '' }}>Amazon S3 (Cloud Scale)</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-2">Determines where the generated Scripts, Audio, Clips, and rendered MP4s are stored.</p>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-indigo-200 transition">
                        Save Settings
                    </button>
                </div>

                <script>
                    function toggleVisibility(id) {
                        const el = document.getElementById(id);
                        if (el.type === 'password') {
                            el.type = 'text';
                        } else {
                            el.type = 'password';
                        }
                    }
                </script>
            </form>
        </div>

        <!-- Social Media Integrations -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center gap-4">
                <div class="h-12 w-12 bg-red-100 text-red-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Connected Accounts</h3>
                    <p class="text-sm text-gray-500 mt-1">Authenticate your social platforms for auto-publishing.</p>
                </div>
            </div>

            <div class="p-6">
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl mb-4">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 bg-red-600 rounded-full flex items-center justify-center text-white font-bold">
                            YT</div>
                        <div>
                            <p class="font-bold text-gray-900">YouTube Data API V3</p>
                            <p class="text-sm text-gray-500">Not connected</p>
                        </div>
                    </div>
                    <button
                        class="bg-white border border-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg shadow-sm hover:bg-gray-50 transition">
                        Connect YouTube
                    </button>
                </div>

                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 bg-black rounded-full flex items-center justify-center text-white font-bold">
                            TK</div>
                        <div>
                            <p class="font-bold text-gray-900">TikTok Content API</p>
                            <p class="text-sm text-gray-500">Not connected</p>
                        </div>
                    </div>
                    <button
                        class="bg-white border border-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg shadow-sm hover:bg-gray-50 transition">
                        Connect TikTok
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>