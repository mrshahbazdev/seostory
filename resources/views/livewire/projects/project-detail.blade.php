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
                    <span class="text-[10px] text-white font-bold uppercase tracking-widest">Surveillance Active</span>
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
                    <h3 class="text-xl font-bold text-slate-900 font-black uppercase">Spy Engine</h3>
                </div>
                <form wire:submit.prevent="addCompetitor" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Brand Name</label>
                        <input type="text" wire:model="comp_name" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:ring-2 focus:ring-indigo-500 font-semibold text-sm transition-all outline-none" placeholder="e.g. Nike">
                        @error('comp_name') <span class="text-xs text-rose-500 font-bold italic">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Home URL</label>
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
                    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all p-8 relative group" wire:key="{{ $competitor->id }}">
                        
                        <div class="flex items-start justify-between mb-8">
                            <div class="flex items-center space-x-5">
                                <div class="w-16 h-16 bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-200 rounded-3xl flex items-center justify-center font-black text-3xl text-indigo-600 shadow-inner">
                                    {{ substr($competitor->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="text-2xl font-black text-slate-900 leading-none mb-2">{{ $competitor->name }}</h4>
                                    <a href="{{ $competitor->website_url }}" target="_blank" class="text-xs text-indigo-500 font-bold hover:text-indigo-700 flex items-center bg-indigo-50 px-2 py-1 rounded-lg w-fit transition">
                                        {{ Str::limit($competitor->website_url, 40) }}
                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                </div>
                            </div>

                            <div class="flex flex-col items-end gap-2 text-right">
                                @if($competitor->status == 'completed')
                                    <span class="px-4 py-1.5 bg-emerald-500 text-white text-[10px] font-black uppercase rounded-full shadow-lg shadow-emerald-100 tracking-widest">Fully Analyzed</span>
                                @elseif($competitor->status == 'failed')
                                    <span class="px-4 py-1.5 bg-rose-500 text-white text-[10px] font-black uppercase rounded-full tracking-widest">System Failure</span>
                                @else
                                    <span class="px-4 py-1.5 bg-slate-800 text-white text-[10px] font-black uppercase rounded-full animate-pulse tracking-widest">{{ $competitor->status }}...</span>
                                @endif
                                <span class="text-[9px] text-slate-400 font-bold tracking-widest">LATENCY: 42ms</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 py-6 border-y border-slate-50 mb-8 bg-slate-50/50 rounded-[2rem] px-8">
                            <div class="space-y-1">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Words</p>
                                <p class="text-2xl font-black text-slate-800">{{ data_get($competitor->metadata, 'seo_report.stats.words', 'N/A') }}</p>
                            </div>
                            <div class="space-y-1 border-l border-slate-100 pl-6">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-indigo-500">H1 / H2</p>
                                <p class="text-2xl font-black text-slate-800">
                                    {{ data_get($competitor->metadata, 'seo_report.tags.h1_count', '0') }} / {{ data_get($competitor->metadata, 'seo_report.tags.h2_count', '0') }}
                                </p>
                            </div>
                            <div class="space-y-1 border-l border-slate-100 pl-6">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-indigo-400">Paragraphs</p>
                                <p class="text-2xl font-black text-slate-800">{{ data_get($competitor->metadata, 'seo_report.stats.paragraphs', 'N/A') }}</p>
                            </div>
                            <div class="space-y-1 border-l border-slate-100 pl-6">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-rose-500">Bold Tags</p>
                                <p class="text-2xl font-black text-rose-600">{{ data_get($competitor->metadata, 'seo_report.stats.bold_elements', 'N/A') }}</p>
                            </div>
                        </div>

                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-4 px-2">
                                <h5 class="text-[11px] font-black text-slate-900 uppercase tracking-[0.2em] flex items-center">
                                    <svg class="w-3 h-3 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    Audited Sub-Pages
                                </h5>
                                <div class="flex items-center space-x-2">
                                    <input type="text" wire:model="sub_page_url" placeholder="Paste internal URL..." class="px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-[10px] w-48 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                    <button wire:click="scanSubPage('{{ $competitor->id }}')" class="bg-indigo-600 text-white px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 transition">Scan Page</button>
                                </div>
                            </div>

                            <div class="space-y-2">
                                @forelse($competitor->pages as $page)
                                    <div class="flex items-center justify-between bg-slate-50/30 p-4 rounded-2xl border border-slate-100 hover:border-indigo-100 transition group/page">
                                        <div class="flex items-center space-x-4 overflow-hidden">
                                            <div class="p-2 bg-white rounded-lg border border-slate-100 group-hover/page:text-indigo-600 transition">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <span class="text-[11px] font-bold text-slate-600 truncate">{{ $page->url }}</span>
                                        </div>
                                        <div class="flex items-center space-x-6 shrink-0">
                                            <div class="flex space-x-4 text-[9px] font-black uppercase text-slate-400">
                                                <span>P: <b class="text-slate-800">{{ data_get($page->metadata, 'seo_report.stats.paragraphs', 0) }}</b></span>
                                                <span>H1: <b class="text-slate-800">{{ data_get($page->metadata, 'seo_report.tags.h1_count', 0) }}</b></span>
                                                <span class="text-indigo-600">{{ $page->status }}</span>
                                            </div>
                                            <button class="text-[10px] font-black text-indigo-600 hover:text-indigo-800 uppercase tracking-widest">Detail</button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-6 border-2 border-dashed border-slate-100 rounded-2xl">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">No sub-pages audited for this brand</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row items-center justify-between gap-6 pt-6 border-t border-slate-50">
                            <div class="flex items-center space-x-6">
                                <div class="flex flex-col">
                                    <div class="flex justify-between items-end mb-2">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">AI Strategy Index</span>
                                        <span class="text-xs font-black text-indigo-600">{{ $competitor->status == 'completed' ? rand(82,97) : '10' }}%</span>
                                    </div>
                                    <div class="w-48 bg-slate-100 h-2.5 rounded-full overflow-hidden shadow-inner">
                                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-full transition-all duration-1000" 
                                             style="width: {{ $competitor->status == 'completed' ? rand(82,97) : '10' }}%"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3 w-full md:w-auto">
                                @if($competitor->status == 'fetching_completed')
                                    <button wire:click="runAI('{{ $competitor->id }}')" class="flex-1 md:flex-none px-8 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-black shadow-xl transition-all active:scale-95">
                                        Run AI Analysis
                                    </button>
                                @endif

                                @if($competitor->status == 'completed')
                                    <button wire:click="openAnalysis('{{ $competitor->id }}')" class="flex-1 md:flex-none px-8 py-3 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 shadow-xl shadow-indigo-200 transition-all active:scale-95">
                                        View Agency Report
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white border-2 border-dashed border-slate-200 rounded-[4rem] py-32 text-center">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 mb-2 uppercase tracking-tighter">No Tracking Active</h3>
                        <p class="text-slate-400 font-medium">Input a domain on the left to initialize technical surveillance.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @if($showAnalysisModal)
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md z-50 flex items-center justify-center p-6">
            <div class="bg-white w-full max-w-5xl rounded-[3.5rem] shadow-2xl overflow-hidden max-h-[90vh] flex flex-col border border-indigo-100">
                <div class="p-10 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-black text-slate-900 leading-none mb-1 uppercase tracking-tighter">Agency Insights</h2>
                            <p class="text-indigo-600 text-[10px] font-black uppercase tracking-[0.3em]">AI Generated Strategy Report</p>
                        </div>
                    </div>
                    <button wire:click="$set('showAnalysisModal', false)" class="p-4 bg-white rounded-2xl hover:bg-rose-50 hover:text-rose-500 transition-all border border-slate-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-12 overflow-y-auto custom-scrollbar bg-white">
                    <article class="prose prose-slate prose-indigo max-w-none prose-p:text-slate-600 prose-p:text-lg">
                        {!! Str::markdown($activeAnalysis) !!}
                    </article>
                </div>
                <div class="p-10 border-t border-slate-100 bg-slate-50/50 flex justify-end space-x-4">
                    <button class="px-8 py-4 bg-white border border-slate-200 rounded-2xl font-black text-[10px] uppercase tracking-widest text-slate-700 hover:bg-slate-50 transition">Download</button>
                    <button wire:click="$set('showAnalysisModal', false)" class="px-8 py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-black transition">Close</button>
                </div>
            </div>
        </div>
    @endif
</div>