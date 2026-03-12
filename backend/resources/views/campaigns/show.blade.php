<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-indigo-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h2 class="text-2xl font-black text-gray-900 leading-tight">
                    {{ $campaign->title }}
                </h2>
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-800 border border-gray-200">
                    {{ $campaign->status }}
                </span>
            </div>
            <button
                class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 font-bold py-2 px-4 rounded-lg shadow-sm transition flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Campaign Settings
            </button>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Settings Summary -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4 border-b pb-2">Topic & Prompt Summary</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Topic</p>
                        <p class="text-sm text-gray-800 font-medium">{{ $campaign->topic }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Niche / Audience</p>
                        <p class="text-sm text-gray-800">{{ $campaign->niche ?? 'General' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Target Platform</p>
                        <p class="text-sm text-gray-800">{{ $campaign->target_platform ?? 'Auto' }}</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-10 rounded-full blur-2xl"></div>
                <h3 class="font-bold mb-2">Automated Publishing</h3>
                <p class="text-indigo-100 text-sm mb-4">You have set this campaign to post automatically upon successful
                    rendering.</p>
                <button
                    class="bg-white text-indigo-900 text-sm font-bold py-2 px-4 rounded-lg shadow w-full hover:bg-gray-50 transition">
                    Pause Publishing
                </button>
            </div>
        </div>

        <!-- Right Column: Generation Pipeline -->
        <div class="lg:col-span-2 space-y-6">
            <h3 class="text-lg font-bold text-gray-900 mb-2">Generated Content</h3>

            @if($campaign->videos->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center text-gray-500">
                    No content has been generated yet.
                </div>
            @else
                @foreach($campaign->videos as $video)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">Generation #{{ $video->id }}</h4>
                                <p class="text-sm text-gray-500">Created {{ $video->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $video->status === 'Published' ? 'bg-green-100 text-green-700' : ($video->status === 'Failed' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">
                                    {{ $video->status }}
                                </span>
                                @if($video->status === 'Failed')
                                    <form method="POST" action="{{ route('videos.retry', $video->id) }}">
                                        @csrf
                                        <button type="submit"
                                            class="bg-indigo-100 text-indigo-700 hover:bg-indigo-600 hover:text-white transition px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                </path>
                                            </svg>
                                            Retry
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <div class="p-6 flex flex-col md:flex-row gap-8">
                            <!-- Media Placeholder/Preview -->
                            <div
                                class="w-full md:w-1/3 aspect-[9/16] bg-gray-900 rounded-xl relative overflow-hidden shadow-inner flex items-center justify-center group flex-shrink-0">
                                @if($video->s3_url)
                                    <video src="{{ $video->s3_url }}" class="absolute inset-0 w-full h-full object-cover opacity-80"
                                        controls></video>
                                @else
                                    <div class="text-center p-4">
                                        <div
                                            class="w-12 h-12 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin mx-auto mb-3">
                                        </div>
                                        <span class="text-sm font-bold text-gray-300">Processing...</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Data / Script -->
                            <div class="flex-1 min-w-0 space-y-4">
                                <div>
                                    <h5 class="text-sm font-bold text-gray-900">Final Title</h5>
                                    <p class="text-gray-700 text-sm mt-1">{{ $video->title ?? 'Pending generation...' }}</p>
                                </div>

                                <!-- Script Preview -->
                                <div>
                                    <h5 class="text-sm font-bold text-gray-900 mb-2">AI Generated Script</h5>
                                    <div
                                        class="bg-gray-50 rounded-xl border border-gray-200 p-4 h-32 overflow-y-auto text-sm text-gray-600 font-mono">
                                        @if($video->status === 'Pending' || $video->status === 'Generating Scripts')
                                            [AI is currently writing the narrative script...]
                                        @else
                                            [Scene 1] Hook: "Did you know that Roman Emperors used the exact same focus techniques
                                            programmers need today?"
                                            <br><br>
                                            [Scene 2] Body: "Marcus Aurelius practiced 'Obstacle is the Way'. When your code breaks,
                                            that bug isn't a blocker, it's the path to understanding the architecture deeper."
                                            <br><br>
                                            [Scene 3] CTA: "Stop being frustrated by errors. Embrace them. Subscribe for more stoic
                                            coding tips."
                                            <br><br>
                                            <i>(Full script stored securely in AWS S3 and passed to TTS engine)</i>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <h5 class="text-sm font-bold text-gray-900 mb-2">Included Assets & Media</h5>
                                    <div class="flex flex-wrap gap-2">
                                        @forelse($video->mediaAssets as $asset)
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                                    </path>
                                                </svg>
                                                {{ ucfirst($asset->type) }}
                                            </span>
                                        @empty
                                            <span class="text-sm text-gray-500 italic">No assets registered yet.</span>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-gray-100">
                                    <h5 class="text-sm font-bold text-gray-900 mb-2">Publishing Status</h5>
                                    @forelse($video->postLogs as $log)
                                        <div class="text-sm flex items-center gap-2 mt-1">
                                            <span
                                                class="w-2 h-2 rounded-full {{ $log->status === 'success' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                            <span class="font-medium text-gray-700">{{ ucfirst($log->platform) }}:</span>
                                            <span class="text-gray-500">{{ $log->status }}
                                                ({{ $log->posted_at ? \Carbon\Carbon::parse($log->posted_at)->format('M d, Y') : 'Pending' }})</span>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500">Not queued for publishing.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>