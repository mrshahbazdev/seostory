<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Enterprise SaaS | Competitor AI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
    @livewireStyles
</head>

<body class="h-full antialiased text-slate-900">
    <div class="flex min-h-screen">
        <div class="hidden md:flex md:w-72 md:flex-col md:fixed md:inset-y-0 bg-navy-800 border-r border-navy-900">
            <div class="flex flex-col flex-grow pt-6 overflow-y-auto">
                <div class="flex items-center flex-shrink-0 px-6 space-x-3">
                    <div
                        class="w-8 h-8 bg-seo-green-500 rounded-lg flex items-center justify-center shadow-lg shadow-green-900/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-white">Seo<span
                            class="text-seo-green-500">bility</span><span
                            class="text-slate-400 text-sm font-normal">.clone</span></span>
                </div>

                <nav class="mt-8 flex-1 px-4 space-y-2">
                    <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Project
                        Management</p>
                    <a href="/dashboard"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg bg-navy-900 text-white border border-navy-900 shadow-sm transition-all">
                        <svg class="mr-3 h-5 w-5 text-seo-green-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                            </path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="#"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg text-slate-300 hover:bg-navy-900 hover:text-white transition-colors">
                        <svg class="mr-3 h-5 w-5 text-slate-500 group-hover:text-seo-green-500 transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Keyword Research
                    </a>
                    <a href="#"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg text-slate-300 hover:bg-navy-900 hover:text-white transition-colors">
                        <svg class="mr-3 h-5 w-5 text-slate-500 group-hover:text-seo-green-500 transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Rankings
                    </a>
                </nav>

                <div class="p-4 bg-navy-900/50 m-4 rounded-xl border border-navy-900">
                    <div class="flex items-center px-2 py-1">
                        <div
                            class="flex-shrink-0 w-8 h-8 bg-navy-800 border border-slate-700 rounded-lg flex items-center justify-center font-bold text-seo-green-500 shadow-sm">
                            {{ substr(Auth::user()->currentTeam->name, 0, 1) }}
                        </div>
                        <div class="ml-3 truncate">
                            <p class="text-xs text-slate-400 uppercase tracking-wider mb-0.5">Workspace</p>
                            <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->currentTeam->name }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="md:pl-72 flex flex-col flex-1">
            <header class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-slate-200">
                <div class="flex-shrink-0 flex h-20 items-center justify-between px-8">
                    <div>
                        @if (isset($header))
                            {{ $header }}
                        @endif
                    </div>
                    <div class="flex items-center space-x-4">
                        @livewire('navigation-menu')
                    </div>
                </div>
            </header>

            <main class="py-10 px-8">
                {{ $slot }}
            </main>
        </div>
    </div>
    @livewireScripts
</body>

</html>