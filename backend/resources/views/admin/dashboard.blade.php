<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs uppercase tracking-[0.28em] text-slate-500">Operations</p>
            <h2 class="font-semibold text-xl leading-tight text-slate-900">
                {{ __('Admin Control Panel') }}
            </h2>
        </div>
    </x-slot>

    <div class="space-y-8 py-4">
        <div class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
            <div class="three-d-card p-8 md:p-10">
                <div class="relative z-10">
                    <div class="pill-badge mb-5">Admin overview</div>
                    <h3 class="section-title text-4xl md:text-5xl">Professional admin panel with premium depth.</h3>
                    <p class="mt-6 max-w-3xl text-base leading-8 text-slate-600">This admin area now looks and feels like a real operations command center, with stronger visual hierarchy, better monitoring cards, and a production process view.</p>
                </div>
            </div>
            <div class="premium-card rounded-3xl p-8">
                <p class="metric-label">Live process</p>
                <div class="mt-5 space-y-4">
                    @foreach([
                        'Input campaign settings',
                        'Generate script and narration',
                        'Create image seeds and Runway clips',
                        'Render final video and publish assets',
                    ] as $step)
                        <div class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="mt-1 h-3 w-3 rounded-full bg-emerald-400 shadow-[0_0_20px_rgba(74,222,128,0.55)]"></div>
                            <p class="text-sm font-medium text-slate-700">{{ $step }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

            <!-- Key Metrics -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="three-d-card p-6">
                    <div class="relative z-10">
                        <h4 class="metric-label">Total Users</h4>
                        <p class="metric-value mt-3">{{ $users->count() }}</p>
                        <p class="mt-2 text-sm text-slate-600">Registered creators and admins in the platform.</p>
                    </div>
                </div>
                <div class="three-d-card p-6">
                    <div class="relative z-10">
                        <h4 class="metric-label">Total Campaigns</h4>
                        <p class="metric-value mt-3">{{ $campaigns->count() }}</p>
                        <p class="mt-2 text-sm text-slate-600">All campaigns currently created across workspaces.</p>
                    </div>
                </div>
                <div class="three-d-card p-6">
                    <div class="relative z-10">
                        <h4 class="metric-label">Active Generators</h4>
                        <p class="metric-value mt-3">{{ $campaigns->where('status', 'Generating')->count() }}</p>
                        <p class="mt-2 text-sm text-slate-600">Pipelines currently rendering or generating scenes.</p>
                    </div>
                </div>
            </div>

            <!-- Global Users Table -->
            <div class="glass-panel-strong overflow-hidden rounded-3xl">
                <div class="p-6 md:p-8">
                    <h3 class="mb-4 border-b border-slate-200 pb-3 text-lg font-bold text-slate-900">Registered Users</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left whitespace-nowrap">
                            <thead>
                                <tr
                                    class="border-b border-slate-200 bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <th class="px-4 py-3">ID</th>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">Email</th>
                                    <th class="px-4 py-3">Role</th>
                                    <th class="px-4 py-3">Campaigns</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($users as $user)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 text-sm text-slate-700">{{ $user->id }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-slate-900">{{ $user->name }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-400">{{ $user->email }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-xs rounded-full {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700 border border-purple-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-400">{{ $user->campaigns_count }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Global Campaigns Table -->
            <div class="glass-panel-strong overflow-hidden rounded-3xl">
                <div class="p-6 md:p-8">
                    <h3 class="mb-4 border-b border-slate-200 pb-3 text-lg font-bold text-slate-900">All Campaigns Overview</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left whitespace-nowrap">
                            <thead>
                                <tr
                                    class="border-b border-slate-200 bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <th class="px-4 py-3">ID</th>
                                    <th class="px-4 py-3">User</th>
                                    <th class="px-4 py-3">Title</th>
                                    <th class="px-4 py-3">Topic</th>
                                    <th class="px-4 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($campaigns as $campaign)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 text-sm text-slate-700">{{ $campaign->id }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-slate-900">{{ $campaign->user->name }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-indigo-700">{{ $campaign->title }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-400">{{ $campaign->topic }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-600">{{ $campaign->status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
</x-app-layout>