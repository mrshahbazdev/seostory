<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Seobility Clone') }} - SEO Analysis Tool</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="antialiased text-slate-600 bg-white">

    <!-- Header -->
    <header class="fixed w-full z-50 bg-white/90 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center gap-2">
                        <div
                            class="w-8 h-8 bg-seo-green-500 rounded-lg flex items-center justify-center shadow-lg shadow-green-500/20">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold tracking-tight text-navy-900">Seo<span
                                class="text-seo-green-500">bility</span></span>
                    </div>
                    <nav class="hidden md:flex ml-10 space-x-8">
                        <a href="#" class="text-sm font-semibold text-slate-500 hover:text-navy-900">Features</a>
                        <a href="#" class="text-sm font-semibold text-slate-500 hover:text-navy-900">Pricing</a>
                        <a href="#" class="text-sm font-semibold text-slate-500 hover:text-navy-900">Academy</a>
                    </nav>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="text-sm font-semibold text-navy-900 hover:text-seo-green-500">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-500 hover:text-navy-900">Log
                            in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="px-5 py-2.5 text-sm font-bold text-white bg-seo-green-500 rounded-lg hover:bg-seo-green-600 transition-colors shadow-lg shadow-green-500/20">Sign
                                Up Free</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 sm:pt-40 sm:pb-24 overflow-hidden bg-navy-900">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10">
            <h1 class="text-4xl sm:text-6xl font-extrabold text-white tracking-tight mb-6">
                Better SEO for your <br class="hidden sm:block" />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-seo-green-500 to-emerald-300">Website
                    Ranking</span>
            </h1>
            <p class="mt-4 text-xl text-slate-300 max-w-2xl mx-auto mb-10">
                Seobility is your all-in-one SEO software for better website optimization. Check your website to analyze
                and improve your search engine rankings.
            </p>

            <div class="max-w-3xl mx-auto">
                <form action="{{ route('register') }}"
                    class="sm:flex items-center gap-2 bg-white/10 p-2 rounded-2xl backdrop-blur-sm border border-white/10">
                    <div class="relative flex-grow">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-slate-400 text-sm font-semibold">https://</span>
                        </div>
                        <input type="text" name="url"
                            class="block w-full pl-20 pr-4 py-4 bg-white rounded-xl border-0 text-navy-900 placeholder:text-slate-400 focus:ring-0 sm:text-lg font-medium"
                            placeholder="www.example.com">
                    </div>
                    <button type="submit"
                        class="mt-2 sm:mt-0 w-full sm:w-auto px-8 py-4 bg-seo-green-500 text-white font-bold rounded-xl hover:bg-seo-green-600 transition-all shadow-xl shadow-green-500/20 text-lg">
                        Analyze Website
                    </button>
                </form>
                <p class="mt-4 text-sm text-slate-400 font-medium">Free SEO Check &bull; No credit card required</p>
            </div>
        </div>

        <!-- Background Decor -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-seo-green-500/20 rounded-full blur-3xl"></div>
            <div
                class="absolute top-1/2 right-0 w-[500px] h-[500px] bg-blue-600/20 rounded-full blur-3xl transform translate-x-1/2">
            </div>
        </div>
    </div>

    <!-- Features Grid -->
    <div class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-navy-900">All-in-One SEO Software</h2>
                <p class="mt-4 text-lg text-slate-500">Everything you need to optimize your website and outperform
                    competitors.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div
                    class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl transition-shadow group">
                    <div
                        class="w-14 h-14 bg-indigo-50 rounded-xl flex items-center justify-center mb-6 group-hover:bg-indigo-100 transition-colors">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-navy-900 mb-3">On-page Audit</h3>
                    <p class="text-slate-500 leading-relaxed">
                        Crawl your entire website and find technical errors and on-page SEO issues.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div
                    class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl transition-shadow group">
                    <div
                        class="w-14 h-14 bg-seo-green-500/10 rounded-xl flex items-center justify-center mb-6 group-hover:bg-seo-green-500/20 transition-colors">
                        <svg class="w-8 h-8 text-seo-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-navy-900 mb-3">Rank Tracking</h3>
                    <p class="text-slate-500 leading-relaxed">
                        Monitor your keyword rankings on Google and track your mobile and desktop success.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div
                    class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl transition-shadow group">
                    <div
                        class="w-14 h-14 bg-orange-50 rounded-xl flex items-center justify-center mb-6 group-hover:bg-orange-100 transition-colors">
                        <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-navy-900 mb-3">Backlink Analysis</h3>
                    <p class="text-slate-500 leading-relaxed">
                        Analyze your backlink profile and spy on your competitors' link building strategies.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-navy-900 border-t border-navy-800 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center">
            <div class="flex items-center gap-2 mb-4 md:mb-0">
                <div class="w-6 h-6 bg-seo-green-500 rounded flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <span class="text-lg font-bold text-white">SeoStory</span>
            </div>
            <div class="text-slate-400 text-sm">
                &copy; {{ date('Y') }} SeoStory. All rights reserved.
            </div>
        </div>
    </footer>
</body>

</html>