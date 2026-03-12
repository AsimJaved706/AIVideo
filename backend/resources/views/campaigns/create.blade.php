<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-indigo-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="text-2xl font-black text-gray-900 leading-tight">
                {{ __('Create New Campaign') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">

        <div
            class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-white shadow-xl relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
            <h3 class="text-2xl font-black mb-2 relative z-10">AI Video Architect</h3>
            <p class="text-indigo-100 relative z-10">Define your topic, audience, and style. Our AI will automatically
                write the script, generate the voiceover, assemble stock footage, and render the final masterpiece.</p>
        </div>

        <form method="POST" action="{{ route('campaigns.store') }}"
            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-8">
            @csrf

            <!-- Campaign Title -->
            <div>
                <label for="title" class="block text-sm font-bold text-gray-900 mb-2">Campaign Internal Name <span
                        class="text-red-500">*</span></label>
                <input type="text" name="title" id="title"
                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 p-3"
                    placeholder="e.g. Daily Stoic Shorts - Week 1" required>
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="border-t border-gray-100 pt-6">
                <h4 class="text-lg font-bold text-gray-900 mb-4">Content Direction</h4>

                <div class="space-y-6">
                    <!-- Topic Prompt -->
                    <div>
                        <label for="topic" class="block text-sm font-bold text-gray-900 mb-2">Video Topic / Prompt <span
                                class="text-red-500">*</span></label>
                        <textarea name="topic" id="topic" rows="4"
                            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 p-3"
                            placeholder="Explain the concept of 'Memento Mori' using modern day examples applicable to software engineers."
                            required></textarea>
                        <p class="text-xs text-gray-500 mt-2">The AI script writer will base the entire narrative on
                            this prompt.</p>
                        @error('topic') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Niche -->
                        <div>
                            <label for="niche" class="block text-sm font-bold text-gray-900 mb-2">Target Audience /
                                Niche</label>
                            <input type="text" name="niche" id="niche"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 p-3"
                                placeholder="e.g. Programmers, Fitness, Finance">
                        </div>

                        <!-- Target Platform -->
                        <div>
                            <label for="target_platform" class="block text-sm font-bold text-gray-900 mb-2">Format &
                                Platform</label>
                            <select name="target_platform" id="target_platform"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 p-3">
                                <option value="TikTok">TikTok (9:16 Vertical)</option>
                                <option value="YouTube Shorts">YouTube Shorts (9:16 Vertical)</option>
                                <option value="Reels">Instagram Reels (9:16 Vertical)</option>
                                <option value="YouTube">YouTube Standard (16:9 Horizontal)</option>
                            </select>
                        </div>
                        
                        <!-- Video Quantity -->
                        <div>
                            <label for="quantity" class="block text-sm font-bold text-gray-900 mb-2">Quantity to Generate <span
                                class="text-red-500">*</span></label>
                            <select name="quantity" id="quantity"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 p-3">
                                <option value="1">1 Video</option>
                                <option value="3">3 Videos (Batch)</option>
                                <option value="5">5 Videos (Batch)</option>
                                <option value="10">10 Videos (Batch)</option>
                                <option value="30">30 Videos (Full Month)</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-2">AI will automatically create unique scripts for each.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-6 flex items-center justify-between">
                <p class="text-sm text-gray-500 italic">Generation typically takes 1-3 minutes depending on API queue.
                </p>
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-indigo-200 transition transform hover:-translate-y-0.5 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Generate Video Now
                </button>
            </div>

        </form>
    </div>
</x-app-layout>