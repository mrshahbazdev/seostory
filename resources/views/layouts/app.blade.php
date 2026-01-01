<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Enterprise SaaS | Competitor AI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
    @livewireStyles
</head>
<body class="h-full antialiased text-slate-900">
    <div class="flex min-h-screen">
        <div class="hidden md:flex md:w-72 md:flex-col md:fixed md:inset-y-0 border-r border-slate-200 bg-white">
            <div class="flex flex-col flex-grow pt-6 overflow-y-auto">
                <div class="flex items-center flex-shrink-0 px-6 space-x-2">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center shadow-lg shadow-indigo-200">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-slate-800">SeoStory<span class="text-indigo-600">.ai</span></span>
                </div>
                
                <nav class="mt-8 flex-1 px-4 space-y-1">
                    <a href="/dashboard" class="group flex items-center px-4 py-3 text-sm font-semibold rounded-xl bg-indigo-50 text-indigo-700">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                    <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-slate-500 hover:bg-slate-50 hover:text-slate-900">
                        <svg class="mr-3 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Competitors
                    </a>
                </nav>

                <div class="p-4 bg-slate-50 m-4 rounded-2xl border border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 px-2">Active Workspace</p>
                    <div class="flex items-center px-2 py-1">
                        <div class="flex-shrink-0 w-8 h-8 bg-white border border-slate-200 rounded-lg flex items-center justify-center font-bold text-indigo-600 shadow-sm">
                            {{ substr(Auth::user()->currentTeam->name, 0, 1) }}
                        </div>
                        <div class="ml-3 truncate">
                            <p class="text-sm font-semibold text-slate-700 truncate">{{ Auth::user()->currentTeam->name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="md:pl-72 flex flex-col flex-1">
            <header class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-slate-200">
                <div class="flex-shrink-0 flex h-20 items-center justify-between px-8">
                    <h2 class="text-lg font-semibold text-slate-800">Project Overview</h2>
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