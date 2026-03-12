<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased bg-white">
    <div class="min-h-screen flex">
        <!-- Left Side / Form -->
        <div
            class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24 w-full lg:w-1/2 relative bg-white z-10">

            <div class="mx-auto w-full max-w-sm lg:w-96">
                <div class="mb-10 text-2xl font-black tracking-tighter text-indigo-900 flex items-center gap-2">
                    <div
                        class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z">
                            </path>
                        </svg>
                    </div>
                    <span>VideoAI</span>
                </div>

                {{ $slot }}

            </div>
        </div>

        <!-- Right Side / Image & Brand -->
        <div class="hidden lg:block relative w-0 flex-1 bg-gradient-to-br from-indigo-900 via-purple-900 to-indigo-800">
            <div class="absolute inset-0 bg-black opacity-20"></div>
            <!-- Abstract elements -->
            <div
                class="absolute top-0 right-0 -mr-48 -mt-48 w-96 h-96 rounded-full bg-purple-500 mix-blend-multiply filter blur-3xl opacity-50">
            </div>
            <div
                class="absolute bottom-0 left-0 -ml-48 -mb-48 w-96 h-96 rounded-full bg-indigo-500 mix-blend-multiply filter blur-3xl opacity-50">
            </div>

            <div class="relative h-full flex flex-col justify-center items-center text-white px-12 z-10 text-center">
                <h2 class="text-4xl font-black tracking-tight mb-4">Unleash Your Creativity</h2>
                <p class="text-lg text-indigo-100 font-medium max-w-md">Join thousands of creators automating their
                    content pipelines with intelligent script generation and rapid video rendering.</p>
            </div>
        </div>
    </div>
</body>

</html>