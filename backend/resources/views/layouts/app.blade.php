<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'VideoAI') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-slate-900 flex h-screen overflow-hidden bg-slate-100">

    <!-- Sidebar -->
    <aside class="w-72 border-r border-slate-200 bg-white text-slate-900 backdrop-blur-xl flex flex-col h-full shadow-lg shadow-slate-200/70 flex-shrink-0">
        <div class="h-20 flex items-center px-8 border-b border-slate-200">
            <div class="text-2xl font-black tracking-tighter flex items-center gap-2">
                <div
                    class="w-10 h-10 rounded-2xl bg-gradient-to-br from-indigo-400 to-purple-500 shadow-glow flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z">
                        </path>
                    </svg>
                </div>
                <div>
                    <div class="text-slate-900">VideoAI</div>
                    <div class="text-[10px] uppercase tracking-[0.24em] text-indigo-500/80">Studio Console</div>
                </div>
            </div>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 font-bold border border-indigo-100 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-700 border border-transparent' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
                Campaigns
            </a>

            <a href="{{ route('settings.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('settings.*') ? 'bg-indigo-50 text-indigo-700 font-bold border border-indigo-100 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-700 border border-transparent' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Settings & API Keys
            </a>

            <a href="{{ route('logs.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('logs.*') ? 'bg-indigo-50 text-indigo-700 font-bold border border-indigo-100 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-700 border border-transparent' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
                Generation Logs
            </a>

            @if(Auth::user() && Auth::user()->role === 'admin')
                <div class="pt-4 mt-4 border-t border-slate-200">
                    <div class="px-4 mb-2 text-xs font-bold text-indigo-500 uppercase tracking-wider">Administration</div>
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.*') ? 'bg-indigo-50 text-indigo-700 font-bold border border-indigo-100 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-700 border border-transparent' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                        Admin Panel
                    </a>
                </div>
            @endif
        </nav>

        <div class="p-4 border-t border-slate-200">
            <div class="three-d-card px-4 py-4">
            <div class="flex items-center gap-3 px-1 py-1">
                <div
                    class="w-10 h-10 rounded-full bg-indigo-700 flex items-center justify-center font-bold border-2 border-indigo-400/60">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit"
                    class="w-full text-left px-4 py-2 text-sm text-slate-600 hover:text-indigo-700 hover:bg-indigo-50 rounded-xl transition">
                    Sign out
                </button>
            </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full overflow-hidden bg-transparent">
        <!-- Top Header (Optional) -->
        @isset($header)
            <header class="top-nav px-8 py-5 flex-shrink-0 flex items-center justify-between">
                <div class="text-2xl font-bold text-slate-900">{{ $header }}</div>
                <div class="hidden md:flex items-center gap-3 text-sm text-slate-600">
                    <span class="rounded-full border border-emerald-400/20 bg-emerald-500/10 px-3 py-1 font-semibold text-emerald-300">System Online</span>
                    <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 font-semibold text-slate-600">Production Workspace</span>
                </div>
            </header>
        @endisset

        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-7xl mx-auto">
                {{ $slot }}
            </div>
        </div>

        <footer class="border-t border-slate-200 bg-white px-8 py-4 text-sm text-slate-500 backdrop-blur-xl">
            <div class="mx-auto flex max-w-7xl flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>VideoAI Studio workspace</div>
                <div>Professional dashboard • automation • rendering pipeline</div>
            </div>
        </footer>
    </main>

</body>

</html>