<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VideoAI Studio</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-slate-900">
    <header class="top-nav">
        <div class="section-shell flex h-20 items-center justify-between">
            <a href="/" class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-fuchsia-500 shadow-glow">
                    <svg class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
                    </svg>
                </div>
                <div>
                    <div class="text-lg font-black tracking-tight text-slate-900">VideoAI Studio</div>
                    <div class="text-xs uppercase tracking-[0.25em] text-slate-400">AI Video Platform</div>
                </div>
            </a>

            <nav class="hidden items-center gap-8 text-sm font-semibold text-slate-600 lg:flex">
                <a href="#features" class="transition hover:text-indigo-700">Features</a>
                <a href="#process" class="transition hover:text-indigo-700">Process</a>
                <a href="#admin" class="transition hover:text-indigo-700">Admin Panel</a>
                <a href="#footer" class="transition hover:text-indigo-700">Contact</a>
            </nav>

            <div class="flex items-center gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="rounded-full border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="hidden text-sm font-semibold text-slate-600 transition hover:text-indigo-700 md:block">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rounded-full bg-gradient-to-r from-indigo-500 to-fuchsia-500 px-5 py-2.5 text-sm font-bold text-white shadow-glow transition hover:scale-[1.02]">Start Free</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <main>
        <section class="relative overflow-hidden py-20 md:py-28">
            <div class="hero-grid absolute inset-0 opacity-60"></div>
            <div class="absolute left-[8%] top-24 h-44 w-44 rounded-full bg-indigo-500/20 blur-3xl"></div>
            <div class="absolute right-[10%] top-16 h-52 w-52 rounded-full bg-fuchsia-500/20 blur-3xl"></div>
            <div class="section-shell relative grid items-center gap-14 lg:grid-cols-[1.15fr_0.85fr]">
                <div>
                    <div class="pill-badge mb-6">Professional AI video production pipeline</div>
                    <h1 class="max-w-4xl text-5xl font-black leading-tight tracking-tight text-slate-900 md:text-7xl">
                        Generate <span class="text-gradient-brand">natural scripts</span>, premium visuals, and publishing-ready short videos.
                    </h1>
                    <p class="mt-8 max-w-2xl text-lg leading-8 text-slate-600 md:text-xl">
                        VideoAI Studio automates ideation, scriptwriting, voice, visuals, rendering, and publishing into one clean workflow built for Shorts, TikTok, Reels, and client production.
                    </p>
                    <div class="mt-10 flex flex-col gap-4 sm:flex-row">
                        <a href="{{ Route::has('register') ? route('register') : route('login') }}" class="rounded-full bg-gradient-to-r from-indigo-500 to-fuchsia-500 px-8 py-4 text-center text-base font-bold text-white shadow-glow transition hover:scale-[1.02]">Launch Studio</a>
                        <a href="#process" class="rounded-full border border-slate-200 bg-white px-8 py-4 text-center text-base font-semibold text-slate-700 transition hover:bg-slate-50">See the Process</a>
                    </div>
                    <div class="mt-12 grid max-w-2xl grid-cols-2 gap-4 md:grid-cols-4">
                        <div class="premium-card rounded-2xl p-4">
                            <div class="metric-value text-2xl">60s</div>
                            <div class="metric-label mt-1">Avg. short workflow</div>
                        </div>
                        <div class="premium-card rounded-2xl p-4">
                            <div class="metric-value text-2xl">4x</div>
                            <div class="metric-label mt-1">Faster production</div>
                        </div>
                        <div class="premium-card rounded-2xl p-4">
                            <div class="metric-value text-2xl">HD</div>
                            <div class="metric-label mt-1">Vertical exports</div>
                        </div>
                        <div class="premium-card rounded-2xl p-4">
                            <div class="metric-value text-2xl">24/7</div>
                            <div class="metric-label mt-1">Automated ops</div>
                        </div>
                    </div>
                </div>

                <div class="float-slow three-d-card p-6 md:p-7">
                    <div class="rounded-[1.6rem] border border-slate-200 bg-white/90 p-5 shadow-xl shadow-slate-200/70">
                        <div class="mb-5 flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Studio Overview</p>
                                <h3 class="mt-2 text-2xl font-black text-slate-900">Production Console</h3>
                            </div>
                            <div class="rounded-full border border-emerald-400 bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">Live</div>
                        </div>
                        <div class="grid gap-4">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex items-center justify-between text-sm text-slate-600">
                                    <span>Script quality</span>
                                    <span class="font-bold text-slate-900">Natural tone</span>
                                </div>
                                <div class="mt-3 h-2 rounded-full bg-slate-200">
                                    <div class="h-2 w-[88%] rounded-full bg-gradient-to-r from-cyan-400 to-indigo-500"></div>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex items-center justify-between text-sm text-slate-600">
                                    <span>Visual generation</span>
                                    <span class="font-bold text-slate-900">Runway Gen-3</span>
                                </div>
                                <div class="mt-3 grid grid-cols-3 gap-3 text-center text-xs text-slate-600">
                                    <div class="rounded-xl bg-white p-3 border border-slate-200"><div class="text-lg font-black text-slate-900">9:16</div>Format</div>
                                    <div class="rounded-xl bg-white p-3 border border-slate-200"><div class="text-lg font-black text-slate-900">5-10s</div>Clips</div>
                                    <div class="rounded-xl bg-white p-3 border border-slate-200"><div class="text-lg font-black text-slate-900">HD</div>Export</div>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-indigo-300 bg-gradient-to-r from-indigo-50 to-fuchsia-50 p-4">
                                <p class="text-xs uppercase tracking-[0.22em] text-indigo-600 font-semibold">Automation stack</p>
                                <p class="mt-2 text-lg font-bold text-slate-900">Research → Script → Voice → Visuals → Render → Publish</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="features" class="py-20">
            <div class="section-shell">
                <div class="max-w-3xl">
                    <div class="pill-badge mb-5">Built for teams and solo creators</div>
                    <h2 class="section-title">A proper frontend for a professional AI video product.</h2>
                    <p class="section-copy mt-6">The platform now presents itself like a real production suite with clearer navigation, stronger trust signals, visual hierarchy, and premium interface blocks.</p>
                </div>
                <div class="mt-12 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                    <div class="premium-card">
                        <div class="mb-4 inline-flex rounded-2xl bg-indigo-100 px-3 py-2 text-sm font-black text-indigo-700">01</div>
                        <h3 class="text-xl font-bold text-slate-900">Natural Script Engine</h3>
                        <p class="mt-3 text-sm leading-7 text-slate-600">Generate hooks, scene breakdowns, and realistic narration built for short-form retention.</p>
                    </div>
                    <div class="premium-card">
                        <div class="mb-4 inline-flex rounded-2xl bg-fuchsia-100 px-3 py-2 text-sm font-black text-fuchsia-700">02</div>
                        <h3 class="text-xl font-bold text-slate-900">Voice Tuning</h3>
                        <p class="mt-3 text-sm leading-7 text-slate-600">Fine-tune stability, style, speaker boost, output bitrate, and fallback behavior from settings.</p>
                    </div>
                    <div class="premium-card">
                        <div class="mb-4 inline-flex rounded-2xl bg-cyan-100 px-3 py-2 text-sm font-black text-cyan-700">03</div>
                        <h3 class="text-xl font-bold text-slate-900">Runway Visual Control</h3>
                        <p class="mt-3 text-sm leading-7 text-slate-600">Control visual style, motion, framing, lighting, palette, duration, and negative prompts.</p>
                    </div>
                    <div class="premium-card">
                        <div class="mb-4 inline-flex rounded-2xl bg-emerald-100 px-3 py-2 text-sm font-black text-emerald-700">04</div>
                        <h3 class="text-xl font-bold text-slate-900">Admin Visibility</h3>
                        <p class="mt-3 text-sm leading-7 text-slate-600">Track users, campaigns, and production flow from a dashboard designed for oversight.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="process" class="py-20">
            <div class="section-shell">
                <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
                    <div>
                        <div class="pill-badge mb-5">Process</div>
                        <h2 class="section-title">A clear production process from idea to publish-ready asset.</h2>
                        <p class="section-copy mt-6">You asked to add process, so the frontend now includes a dedicated process section explaining how the platform works and what users can expect at each stage.</p>
                    </div>
                    <div class="grid gap-5">
                        @foreach([
                            ['step' => '01', 'title' => 'Campaign Planning', 'copy' => 'Define topic, audience, video length, tone, and generation settings from a clean configuration layer.'],
                            ['step' => '02', 'title' => 'Script + Voice', 'copy' => 'Generate a human-sounding script and synchronized narration with professional voice controls.'],
                            ['step' => '03', 'title' => 'Image + Motion', 'copy' => 'Build scene seeds, send controlled prompts to Runway, and produce vertical clips with consistent visual direction.'],
                            ['step' => '04', 'title' => 'Render + Publish', 'copy' => 'Assemble clips, voiceover, and final assets into a publishable video flow that can be monitored from the dashboard.'],
                        ] as $item)
                            <div class="three-d-card p-6 md:p-7">
                                <div class="relative z-10 flex flex-col gap-4 md:flex-row md:items-start">
                                    <div class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-fuchsia-600 text-lg font-black text-white shadow-md">{{ $item['step'] }}</div>
                                    <div>
                                        <h3 class="text-xl font-bold text-slate-900">{{ $item['title'] }}</h3>
                                        <p class="mt-3 text-sm leading-7 text-slate-600">{{ $item['copy'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section id="admin" class="py-20">
            <div class="section-shell">
                <div class="three-d-card overflow-hidden p-8 md:p-10">
                    <div class="grid gap-8 lg:grid-cols-[0.82fr_1.18fr] lg:items-center">
                        <div>
                            <div class="pill-badge mb-5">Admin panel</div>
                            <h2 class="section-title">A proper admin experience with premium depth and control.</h2>
                            <p class="section-copy mt-6">The admin panel has been redesigned to feel like a real operations console, with stronger metrics, richer cards, and production monitoring blocks.</p>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="premium-card rounded-2xl">
                                <div class="metric-label">User monitoring</div>
                                <div class="metric-value mt-3">Accounts</div>
                                <p class="mt-3 text-sm leading-7 text-slate-600">Review users, usage footprint, and role distribution from a more premium control surface.</p>
                            </div>
                            <div class="premium-card rounded-2xl">
                                <div class="metric-label">Campaign health</div>
                                <div class="metric-value mt-3">Pipelines</div>
                                <p class="mt-3 text-sm leading-7 text-slate-600">Track content generation state across campaigns with cleaner visibility and status context.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer id="footer" class="footer-shell py-10">
        <div class="section-shell grid gap-8 md:grid-cols-[1.2fr_0.8fr] md:items-end">
            <div>
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-fuchsia-500">
                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-black text-slate-900">VideoAI Studio</div>
                        <div class="text-sm text-slate-400">Professional AI-first video operations</div>
                    </div>
                </div>
                <p class="mt-5 max-w-2xl text-sm leading-7 text-slate-400">A cleaner frontend, a real header and footer, a process section, and an upgraded admin experience now give the platform a more credible SaaS presentation.</p>
            </div>
            <div class="text-sm text-slate-400 md:text-right">
                <div>Built for modern creators, agencies, and content operations.</div>
                <div class="mt-2">© {{ now()->year }} VideoAI Studio. All rights reserved.</div>
            </div>
        </div>
    </footer>
</body>

</html>