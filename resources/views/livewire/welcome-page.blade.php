<div class="relative min-h-screen bg-[#030712] overflow-hidden selection:bg-indigo-500/30">
    
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-600/20 rounded-full blur-[120px] -z-10"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-600/10 rounded-full blur-[120px] -z-10"></div>

    <nav class="flex items-center justify-between px-8 py-6 max-w-7xl mx-auto relative z-10">
        <div class="flex items-center gap-2">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <span class="text-xl font-bold text-white tracking-tight">SEO<span class="text-indigo-500">Story</span></span>
        </div>
        <div class="flex items-center gap-6">
            <a href="{{ route('login') }}" class="text-slate-400 hover:text-white transition font-medium">Log in</a>
            <a href="{{ route('register') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-semibold transition shadow-lg shadow-indigo-500/25 text-sm">Get Started</a>
        </div>
    </nav>

    <main class="relative z-10 max-w-7xl mx-auto px-6 pt-20 pb-32 text-center lg:text-left grid lg:grid-cols-2 gap-12 items-center">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-bold uppercase tracking-widest mb-6">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                </span>
                Powered by Gemini 1.5 Flash
            </div>
            <h1 class="text-5xl lg:text-7xl font-extrabold text-white leading-[1.1] mb-6">
                Decode Your <br/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">Competitors' Strategy</span>
            </h1>
            <p class="text-slate-400 text-lg lg:text-xl max-w-xl mb-10 leading-relaxed">
                SEOStory is an AI-driven intelligence platform that crawls any website, analyzes their marketing DNA, and gives you a step-by-step roadmap to outrank them.
            </p>
            
            <div class="flex flex-col sm:flex-row items-center gap-4 justify-center lg:justify-start">
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-white text-slate-900 rounded-2xl font-bold text-lg hover:bg-indigo-50 transition-all shadow-xl">
                    Start Scanning Now
                </a>
                <div class="flex -space-x-3">
                    <div class="w-10 h-10 rounded-full border-2 border-slate-900 bg-slate-800 flex items-center justify-center text-[10px] text-white font-bold">AI</div>
                    <div class="w-10 h-10 rounded-full border-2 border-slate-900 bg-indigo-600 flex items-center justify-center text-[10px] text-white font-bold">SEO</div>
                </div>
                <span class="text-slate-500 text-sm ml-2">Trusted by 500+ SEO Artisans</span>
            </div>
        </div>

        <div class="relative group">
            <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-[2.5rem] blur opacity-25 group-hover:opacity-50 transition duration-1000"></div>
            <div class="relative bg-slate-900 border border-slate-800 rounded-[2rem] p-8 shadow-2xl">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex gap-2">
                        <div class="w-3 h-3 rounded-full bg-red-500/50"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-500/50"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500/50"></div>
                    </div>
                    <div class="text-slate-500 text-xs font-mono">analysis_engine.v2</div>
                </div>
                
                <div class="space-y-6">
                    <div class="h-4 bg-slate-800 rounded-full w-3/4 animate-pulse"></div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="h-20 bg-indigo-500/10 border border-indigo-500/20 rounded-2xl flex flex-col items-center justify-center">
                            <span class="text-indigo-400 font-bold text-xl">94%</span>
                            <span class="text-[10px] text-slate-500 uppercase tracking-tighter">SEO Health</span>
                        </div>
                        <div class="h-20 bg-purple-500/10 border border-purple-500/20 rounded-2xl flex flex-col items-center justify-center">
                            <span class="text-purple-400 font-bold text-xl">AI</span>
                            <span class="text-[10px] text-slate-500 uppercase tracking-tighter">Insights</span>
                        </div>
                        <div class="h-20 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex flex-col items-center justify-center">
                            <span class="text-emerald-400 font-bold text-xl">Top</span>
                            <span class="text-[10px] text-slate-500 uppercase tracking-tighter">Ranking</span>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-950 rounded-xl border border-slate-800 text-left">
                        <p class="text-xs font-mono text-indigo-400 mb-2">> Fetching Competitor DNA...</p>
                        <p class="text-xs font-mono text-slate-400 leading-relaxed">AI Analysis: Nike's primary strength is localized e-commerce funneling and high-authority brand storytelling...</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <section class="bg-slate-950/50 py-24 border-y border-slate-800/50">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-white mb-16">3 Steps to Market Dominance</h2>
            <div class="grid md:grid-cols-3 gap-12">
                <div class="p-8 rounded-3xl bg-slate-900/40 border border-slate-800 hover:border-indigo-500/50 transition duration-500">
                    <div class="w-12 h-12 bg-indigo-600/20 text-indigo-500 rounded-full flex items-center justify-center mb-6 mx-auto font-bold">1</div>
                    <h3 class="text-xl font-bold text-white mb-4">Input URL</h3>
                    <p class="text-slate-400 text-sm">Simply paste your competitor's website address into your project dashboard.</p>
                </div>
                <div class="p-8 rounded-3xl bg-slate-900/40 border border-slate-800 hover:border-indigo-500/50 transition duration-500">
                    <div class="w-12 h-12 bg-purple-600/20 text-purple-500 rounded-full flex items-center justify-center mb-6 mx-auto font-bold">2</div>
                    <h3 class="text-xl font-bold text-white mb-4">AI Deep Scan</h3>
                    <p class="text-slate-400 text-sm">Our bots crawl their content while Gemini AI analyzes their business model and SEO hooks.</p>
                </div>
                <div class="p-8 rounded-3xl bg-slate-900/40 border border-slate-800 hover:border-indigo-500/50 transition duration-500">
                    <div class="w-12 h-12 bg-emerald-600/20 text-emerald-500 rounded-full flex items-center justify-center mb-6 mx-auto font-bold">3</div>
                    <h3 class="text-xl font-bold text-white mb-4">Win the Market</h3>
                    <p class="text-slate-400 text-sm">Get a detailed PDF/Report explaining exactly what you need to do to outsmart them.</p>
                </div>
            </div>
        </div>
    </section>
</div>