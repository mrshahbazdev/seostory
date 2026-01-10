<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 flex justify-between items-end">
                <div>
                    <h1 class="text-2xl font-bold text-navy-900 tracking-tight">Dashboard Overview</h1>
                    <p class="text-slate-500 font-medium mt-1">Welcome back, {{ Auth::user()->name }}</p>
                </div>
                <div class="hidden sm:block">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                        System Operational
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Quick Actions & Stats -->
                <div class="space-y-6">
                    <!-- Quick Audit Widget -->
                    <div
                        class="bg-navy-900 rounded-2xl p-6 text-white shadow-xl shadow-navy-900/10 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <svg class="w-24 h-24 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <div class="relative z-10">
                            <h3 class="font-bold text-lg mb-1">Quick Audit</h3>
                            <p class="text-slate-400 text-sm mb-4">Analyze any URL instantly.</p>
                            <form action="{{ route('audit.check') }}" method="GET">
                                <div class="relative">
                                    <input type="text" name="url" placeholder="domain.com"
                                        class="w-full bg-navy-800/50 border border-navy-700/50 rounded-xl text-white placeholder-slate-500 focus:ring-seo-green-500 focus:border-seo-green-500 py-3 pl-4 pr-16 text-sm transition-all"
                                        autocomplete="off">
                                    <button type="submit"
                                        class="absolute right-1.5 top-1.5 bottom-1.5 bg-seo-green-500 hover:bg-seo-green-600 text-white px-4 rounded-lg text-sm font-bold transition-all shadow-lg shadow-seo-green-500/20">
                                        Check
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Mini Stats -->
                    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                        <h3 class="font-bold text-navy-900 mb-4 text-sm uppercase tracking-wider">Account Limits</h3>
                        <div class="space-y-5">
                            <div>
                                <div class="flex justify-between text-sm mb-1.5">
                                    <span class="text-slate-500 font-medium">Projects</span>
                                    <span class="font-bold text-navy-900">1 / 5</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5">
                                    <div class="bg-navy-800 h-1.5 rounded-full" style="width: 20%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-1.5">
                                    <span class="text-slate-500 font-medium">Crawl Budget</span>
                                    <span class="font-bold text-navy-900">120 / 500</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5">
                                    <div class="bg-seo-green-500 h-1.5 rounded-full" style="width: 24%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Support / Help -->
                    <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-2xl p-6 border border-indigo-100">
                        <h4 class="font-bold text-navy-900 mb-2">Need Help?</h4>
                        <p class="text-sm text-slate-600 mb-4">Check our documentation for advanced SEO guides.</p>
                        <a href="#" class="text-sm font-bold text-indigo-600 hover:text-indigo-700">Read Documentation
                            &rarr;</a>
                    </div>
                </div>

                <!-- Center/Right Column: Projects -->
                <div class="lg:col-span-2">
                    @livewire('projects.project-manager')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>