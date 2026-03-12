<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Campaign Manager') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 flex justify-between items-center">
                    <h3 class="text-lg font-bold">Your Campaigns</h3>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        + New Campaign
                    </button>
                </div>
            </div>

            <!-- Campaigns Table Mockup -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead>
                            <tr class="border-b">
                                <th class="pb-3 text-sm font-semibold uppercase tracking-wide text-gray-500">Title</th>
                                <th class="pb-3 text-sm font-semibold uppercase tracking-wide text-gray-500">Topic</th>
                                <th class="pb-3 text-sm font-semibold uppercase tracking-wide text-gray-500">Platform
                                </th>
                                <th class="pb-3 text-sm font-semibold uppercase tracking-wide text-gray-500">Status</th>
                                <th class="pb-3 text-sm font-semibold uppercase tracking-wide text-gray-500 w-24">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Mock Data Row -->
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-4">Auto-Motivation Shorts #1</td>
                                <td class="py-4">Stoicism applied to modern life</td>
                                <td class="py-4">TikTok</td>
                                <td class="py-4"><span
                                        class="bg-yellow-100 text-yellow-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">Generating</span>
                                </td>
                                <td class="py-4">
                                    <button
                                        class="text-indigo-600 hover:text-indigo-900 border px-2 py-1 rounded text-sm">View
                                        Pipeline</button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="py-4">Fitness Grind Daily</td>
                                <td class="py-4">10 minute home workouts</td>
                                <td class="py-4">YouTube Shorts</td>
                                <td class="py-4"><span
                                        class="bg-green-100 text-green-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">Published</span>
                                </td>
                                <td class="py-4">
                                    <button
                                        class="text-indigo-600 hover:text-indigo-900 border px-2 py-1 rounded text-sm">Analytics</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 text-sm text-gray-500">
                Note: This Dashboard UI connects to the REST APIs defined in <code>routes/api.php</code>.
            </div>
        </div>
    </div>
</x-app-layout>