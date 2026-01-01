<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="bg-gray-50 font-sans antialiased">
        <div class="flex h-screen overflow-hidden">
            <aside class="w-64 bg-slate-900 text-white flex-shrink-0 hidden md:flex flex-col">
                <div class="p-6 text-xl font-bold border-b border-slate-800">
                    🚀 Competitor<span class="text-indigo-400">AI</span>
                </div>
                <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                    <a href="/dashboard" class="flex items-center p-3 text-sm font-medium bg-indigo-600 rounded-lg">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                    <a href="#" class="flex items-center p-3 text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white rounded-lg transition">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Analytics
                    </a>
                </nav>
                <div class="p-4 border-t border-slate-800">
                    <div class="text-xs text-slate-500 uppercase font-bold mb-2">Workspace</div>
                    <div class="flex items-center text-sm">
                        <div class="w-8 h-8 rounded bg-indigo-500 flex items-center justify-center mr-2">
                            {{ substr(Auth::user()->currentTeam->name, 0, 1) }}
                        </div>
                        <span class="truncate">{{ Auth::user()->currentTeam->name }}</span>
                    </div>
                </div>
            </aside>

            <main class="flex-1 flex flex-col overflow-hidden">
                <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-8">
                    <div class="text-gray-600 font-medium">Welcome back, {{ Auth::user()->name }}!</div>
                    <div class="flex items-center space-y-4">
                        @livewire('navigation-menu')
                    </div>
                </header>

                <div class="flex-1 overflow-x-hidden overflow-y-auto p-8">
                    {{ $slot }}
                </div>
            </main>
        </div>
        @livewireScripts
    </body>
</html>