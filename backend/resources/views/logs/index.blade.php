<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-black text-gray-900 leading-tight">
            {{ __('Generation Logs') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        @if($failedVideos->isEmpty())
            <div class="bg-green-50 rounded-2xl p-8 text-center border border-green-100">
                <div
                    class="inline-flex w-16 h-16 bg-green-100 text-green-600 rounded-full items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Zero System Errors</h3>
                <p class="text-gray-500 mt-2 max-w-sm mx-auto">All of your recent AI video generations have completed
                    successfully without any pipeline failures.</p>
            </div>
        @else
            @foreach($failedVideos as $video)
                <div class="bg-white rounded-2xl shadow-sm border border-red-200 overflow-hidden">
                    <div class="bg-red-50 p-4 border-b border-red-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-red-100 text-red-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Generation Failed</h3>
                                <p class="text-sm text-gray-500">{{ $video->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <a href="{{ route('campaigns.show', $video->campaign_id) }}"
                            class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-lg hover:bg-indigo-100 transition">View
                            Campaign</a>
                    </div>

                    <div class="p-6">
                        <div class="mb-4">
                            <p class="text-sm font-semibold text-gray-700">Campaign:</p>
                            <p class="text-gray-900">{{ $video->campaign->title }}</p>
                        </div>

                        <div class="bg-gray-900 rounded-xl p-4 overflow-x-auto">
                            <h4 class="text-xs font-mono text-gray-400 mb-2">// Python Traceback</h4>
                            <pre
                                class="text-sm font-mono text-red-400 whitespace-pre-wrap">{{ $video->error_message ?: 'Unknown background worker error. Check uvicorn terminal console.' }}</pre>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</x-app-layout>