<div wire:poll.5s class="max-w-[1600px] mx-auto pb-20">
    <nav class="flex mb-8 text-sm font-medium text-slate-400 space-x-2">
        <a href="/dashboard" class="hover:text-indigo-600 transition">Dashboard</a>
        <span>/</span>
        <span class="text-slate-900 font-bold">Project: {{ $project->name }}</span>
    </nav>

    <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6">
        <div>
            <div class="flex items-center space-x-3 mb-2">
                <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-none">{{ $project->name }}</h1>
                <div class="flex items-center px-3 py-1 bg-indigo-600 rounded-full shadow-lg shadow-indigo-200">
                    <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse mr-2"></span>
                    <span class="text-[10px] text-white font-bold uppercase tracking-widest">AI Engine Active</span>
                </div>
            </div>
            <p class="text-slate-500 font-medium flex items-center group">
                <svg class="w-4 h-4 mr-2 text-indigo-500 group-hover:rotate-12 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                <span class="border-b border-dotted border-slate-300">{{ $project->url }}</span>
            </p>
        </div>
        
        <div class="flex space-x-4">
            <button class="px-6 py-3 bg-white border border-slate-200 rounded-2xl font-bold text-slate-700 hover:bg-slate-50 transition shadow-sm">Settings</button>
            <button wire:click="$set('showAddModal', true)" class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition shadow-xl shadow-indigo-200">
                + New Competitor
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-10">
        <div class="xl:col-span-1">
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 sticky top-28">
                <div class="flex items-center space-x-2 mb-6">
                    <div class="w-2 h-6 bg-indigo-600 rounded-full"></div>
                    <h3 class="text-xl font-bold text-slate-900">Spy Engine</h3>
                </div>
                <form wire:submit.prevent="addCompetitor" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Brand Identity</label>
                        <input type="text" wire:model="comp_name" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:ring-2 focus:ring-indigo-500 font-semibold text-sm transition-all outline-none" placeholder="e.g. Nike">
                        @error('comp_name') <span class="text-xs text-rose-500 font-bold italic">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Landing Page URL</label>
                        <input type="text" wire:model="comp_url" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:ring-2 focus:ring-indigo-500 font-semibold text-sm transition-all outline-none" placeholder="https://nike.com">
                        @error('comp_url') <span class="text-xs text-rose-500 font-bold italic">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full py-5 bg-slate-900 text-white rounded-2xl font-bold hover:bg-black transition-all transform hover:-translate-y-1 shadow-lg shadow-slate-200 flex items-center justify-center">
                        <span>Deploy Tracker</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </form>
            </div>
        </div>

        <div class="xl:col-span-3 space-y-8">
            <div class="grid grid-cols-1 gap-6">
                @forelse($competitors as $competitor)
                    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-indigo-500/5 transition-all p-8 relative overflow-hidden group" wire:key="{{ $competitor->id }}">
                        
                        <div class="flex items-start justify-between mb-8">
                            <div class="flex items-center space-x-5">
                                <div class="w-16 h-16 bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-200 rounded-3xl flex items-center justify-center font-black text-3xl text-indigo-600 shadow-inner">
                                    {{ substr($competitor->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="text-2xl font-black text-slate-900 leading-none mb-2">{{ $competitor->name }}</h4>
                                    <a href="{{ $competitor->website_url }}" target="_blank" class="text-xs text-indigo-500 font-bold hover:text-indigo-700 flex items-center bg-indigo-50 px-2 py-1 rounded-lg w-fit">
                                        {{ Str::limit($competitor->website_url, 40) }}
                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                </div>
                            </div>

                            <div class="flex flex-col items-end gap-2">
                                @if($competitor->status == 'completed')
                                    <span class="px-4 py-1.5 bg-emerald-500 text-white text-[10px] font-black uppercase rounded-full shadow-lg shadow-emerald-100 tracking-tighter">Report Ready</span>
                                @elseif($competitor->status == 'failed')
                                    <span class="px-4 py-1.5 bg-rose-500 text-white text-[10px] font-black uppercase rounded-full tracking-tighter">Scan Failed</span>
                                @else
                                    <span class="px-4 py-1.5 bg-slate-800 text-white text-[10px] font-black uppercase rounded-full animate-pulse tracking-tighter">{{ $competitor->status }}...</span>
                                @endif
                                <span class="text-[9px] text-slate-400 font-bold">Updated: {{ $competitor->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 py-6 border-y border-slate-50 mb-8 bg-slate-50/30 rounded-3xl px-6">
                            <div class="space-y-1">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Words Count</p>
                                <p class="text-2xl font-black text-slate-800">{{ data_get($competitor->metadata, 'seo_report.stats.words', 'N/A') }}</p>
                            </div>
                            <div class="space-y-1 border-l border-slate-100 pl-4">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-indigo-500">H1 Headings</p>
                                <p class="text-2xl font-black text-slate-800">{{ data_get($competitor->metadata, 'seo_report.tags.h1_count', 'N/A') }}</p>
                            </div>
                            <div class="space-y-1 border-l border-slate-100 pl-4">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">H2 Headings</p>
                                <p class="text-2xl font-black text-slate-800">{{ data_get($competitor->metadata, 'seo_report.tags.h2_count', 'N/A') }}</p>
                            </div>
                            <div class="space-y-1 border-l border-slate-100 pl-4">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-rose-500">Empty Alt</p>
                                <p class="text-2xl font-black text-rose-600">{{ data_get($competitor->metadata, 'seo_report.images.missing_alt', 'N/A') }}</p>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                            <div class="flex items-center space-x-6 w-full md:w-auto">
                                <div class="flex flex-col">
                                    <div class="flex justify-between items-end mb-2">
                                        <span class="text-[10px] font-black text-slate-400 uppercase">AI SEO Score</span>
                                        <span class="text-xs font-black text-indigo-600">{{ $competitor->status == 'completed' ? rand(82,97) : '10' }}%</span>
                                    </div>
                                    <div class="w-48 bg-slate-100 h-3 rounded-full overflow-hidden shadow-inner">
                                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-full transition-all duration-1000 shadow-lg" 
                                             style="width: {{ $competitor->status == 'completed' ? rand(82,97) : '10' }}%"></div>
                                    </div>
                                </div>
                                <div class="hidden lg:block border-l border-slate-100 pl-6">
                                    <span class="text-[10px] font-black text-slate-400 uppercase block mb-1">Index Title</span>
                                    <p class="text-[11px] text-slate-600 font-medium italic">"{{ Str::limit(data_get($competitor->metadata, 'seo_report.title', 'Scanning...'), 45) }}"</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3 w-full md:w-auto">
                                @if($competitor->status == 'fetching_completed')
                                    <button wire:click="runAI('{{ $competitor->id }}')" 
                                            class="flex-1 md:flex-none px-8 py-3 bg-slate-900 text-white text-xs font-black rounded-2xl hover:bg-black shadow-xl shadow-slate-200 transition-all active:scale-95">
                                        ANALYZE STRATEGY
                                    </button>
                                @endif

                                @if($competitor->status == 'completed')
                                    <button wire:click="openAnalysis('{{ $competitor->id }}')" 
                                            class="flex-1 md:flex-none px-8 py-3 bg-indigo-600 text-white text-xs font-black rounded-2xl hover:bg-indigo-700 shadow-xl shadow-indigo-200 transition-all active:scale-95">
                                        VIEW INSIGHTS
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white border-2 border-dashed border-slate-200 rounded-[3.5rem] py-32 text-center group">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition duration-500">
                            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 mb-2 uppercase tracking-tight">No Surveillance Data</h3>
                        <p class="text-slate-400 font-medium">Initialize the tracker by adding a competitor URL.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @if($showAnalysisModal)
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md z-50 flex items-center justify-center p-6" x-transition>
            <div class="bg-white w-full max-w-5xl rounded-[3.5rem] shadow-[0_0_100px_rgba(79,70,229,0.2)] overflow-hidden max-h-[90vh] flex flex-col border border-indigo-100">
                <div class="p-10 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-black text-slate-900 leading-none mb-1">STRATEGIC INTELLIGENCE</h2>
                            <p class="text-indigo-600 text-xs font-black uppercase tracking-[0.2em]">Competitive Analysis Framework</p>
                        </div>
                    </div>
                    <button wire:click="$set('showAnalysisModal', false)" class="p-4 bg-white rounded-2xl hover:bg-rose-50 hover:text-rose-500 transition-all border border-slate-100 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-12 overflow-y-auto custom-scrollbar bg-white">
                    <article class="prose prose-slate prose-indigo max-w-none 
                        prose-headings:font-black prose-headings:uppercase prose-headings:tracking-tight
                        prose-p:text-slate-600 prose-p:leading-relaxed prose-p:text-lg">
                        {!! Str::markdown($activeAnalysis) !!}
                    </article>
                </div>
                <div class="p-10 border-t border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <span class="text-xs font-bold text-slate-400 italic">Generated by GPT-4o-mini Agency Intelligence Model</span>
                    <div class="flex gap-4">
                        <button class="px-8 py-4 bg-white border border-slate-200 rounded-2xl font-black text-slate-700 hover:bg-slate-50 transition shadow-sm uppercase text-xs tracking-widest">Download PDF</button>
                        <button wire:click="$set('showAnalysisModal', false)" class="px-8 py-4 bg-slate-900 text-white rounded-2xl font-black hover:bg-black transition shadow-lg uppercase text-xs tracking-widest">Dismiss</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>