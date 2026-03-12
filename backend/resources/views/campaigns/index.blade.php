<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-black text-gray-900 leading-tight">
            {{ __('Campaign Manager') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-700">Your Campaigns</h3>
            <a href="{{ route('campaigns.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-indigo-200 transition text-sm">
                + New Campaign
            </a>
        </div>

        @if($campaigns->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <div
                    class="w-16 h-16 bg-indigo-50 text-indigo-400 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                </div>
                <h4 class="text-lg font-bold text-gray-900 mb-2">No campaigns yet</h4>
                <p class="text-gray-500 max-w-sm mx-auto">Create your first campaign to start generating AI videos
                    automatically.</p>
            </div>
        @else
            <!-- Campaigns Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500">Campaign</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500">Topic</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500">Platform</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500">Status</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 text-right">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($campaigns as $campaign)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-gray-900">{{ $campaign->title }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">{{ Str::limit($campaign->topic, 30) }}</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $campaign->target_platform ?? 'Auto' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($campaign->status === 'Generating' || $campaign->status === 'Pending')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                {{ $campaign->status }}
                                            </span>
                                        @elseif($campaign->status === 'Completed' || $campaign->status === 'Published')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                                {{ $campaign->status }}
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700 border border-gray-200">
                                                {{ $campaign->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('campaigns.show', $campaign->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900 font-semibold text-sm hover:underline">View
                                            Details -></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>