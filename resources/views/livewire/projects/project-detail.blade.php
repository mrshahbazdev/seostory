<div class="relative min-h-screen">
    {{-- Main Content Area: Blur ho jayega agar verify nahi hai --}}
    <div wire:poll.5s class="max-w-[1600px] mx-auto pb-20 transition-all duration-500 {{ !$project->is_verified ? 'filter blur-md pointer-events-none select-none' : '' }}">
        
        <nav class="flex mb-8 text-sm font-medium text-slate-400 space-x-2 px-4">
            <a href="/dashboard" class="hover:text-indigo-600 transition">Dashboard</a>
            <span>/</span>
            <span class="text-slate-900 font-bold italic underline">Project: {{ $project->name }}</span>
        </nav>

        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6 px-4">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-none">{{ $project->name }}</h1>
                    <div class="flex items-center px-3 py-1 bg-emerald-600 rounded-full shadow-lg shadow-emerald-200">
                        <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse mr-2"></span>
                        <span class="text-[10px] text-white font-bold uppercase tracking-widest italic">SEO Monitor Live</span>
                    </div>
                </div>
                <p class="text-slate-500 font-medium flex items-center group">
                    <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    <span class="border-b border-dotted border-slate-300 font-bold text-indigo-600">{{ $project->url }}</span>
                </p>
            </div>
            
            <div class="flex space-x-4">
                <button class="px-6 py-3 bg-white border border-slate-200 rounded-2xl font-bold text-slate-700 hover:bg-slate-50 transition shadow-sm">Audit Settings</button>
                <button wire:click="$set('showAddModal', true)" class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition shadow-xl shadow-indigo-200">
                    + New Competitor
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-4 gap-10 px-4">
            <div class="xl:col-span-1">
                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 sticky top-28">
                    <div class="flex items-center space-x-2 mb-6">
                        <div class="w-2 h-6 bg-indigo-600 rounded-full"></div>
                        <h3 class="text-xl font-black text-slate-900 uppercase italic tracking-tighter">Add Target</h3>
                    </div>
                    <form wire:submit.prevent="addCompetitor" class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Brand Name</label>
                            <input type="text" wire:model="comp_name" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:ring-2 focus:ring-indigo-500 font-semibold text-sm outline-none" placeholder="e.g. Nike">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Main URL</label>
                            <input type="text" wire:model="comp_url" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:ring-2 focus:ring-indigo-500 font-semibold text-sm outline-none" placeholder="https://nike.com">
                        </div>
                        <button type="submit" class="w-full py-5 bg-slate-900 text-white rounded-2xl font-bold hover:bg-black transition transform hover:-translate-y-1 shadow-lg">
                            Deploy Surveillance 🚀
                        </button>
                    </form>
                </div>
            </div>

            <div class="xl:col-span-3 space-y-8">
                @forelse($competitors as $competitor)
                    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8 relative" wire:key="{{ $competitor->id }}">
                        <div class="flex items-start justify-between mb-8">
                            <div class="flex items-center space-x-5">
                                <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-3xl flex items-center justify-center font-black text-3xl text-indigo-600 shadow-inner italic">
                                    {{ substr($competitor->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="text-2xl font-black text-slate-900 mb-1 tracking-tighter">{{ $competitor->name }}</h4>
                                    <span class="text-xs text-indigo-500 font-bold bg-indigo-50 px-3 py-1 rounded-full italic">{{ $competitor->website_url }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-4 py-1.5 {{ $competitor->status == 'completed' ? 'bg-emerald-500' : 'bg-slate-800' }} text-white text-[10px] font-black uppercase rounded-full tracking-widest animate-pulse">
                                    {{ $competitor->status }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 py-6 border-y border-slate-50 mb-8 bg-slate-50/50 rounded-3xl px-8 shadow-inner">
                            <div class="space-y-1">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Word Count</p>
                                <p class="text-2xl font-black text-slate-800">{{ data_get($competitor->metadata, 'seo_report.stats.words', 'N/A') }}</p>
                            </div>
                            <div class="space-y-1 border-l border-slate-200 pl-6">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-indigo-600 italic">H1 Tags</p>
                                <p class="text-2xl font-black text-slate-800">{{ data_get($competitor->metadata, 'seo_report.tags.h1_count', '0') }}</p>
                            </div>
                            <div class="space-y-1 border-l border-slate-200 pl-6">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Paragraphs</p>
                                <p class="text-2xl font-black text-slate-800 font-serif italic">{{ data_get($competitor->metadata, 'seo_report.stats.paragraphs', 'N/A') }}</p>
                            </div>
                            <div class="space-y-1 border-l border-slate-200 pl-6">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-rose-500">Broken Alt</p>
                                <p class="text-2xl font-black text-rose-600">{{ data_get($competitor->metadata, 'seo_report.images.missing_alt', 'N/A') }}</p>
                            </div>
                        </div>

                        <div class="mb-8 p-6 bg-slate-900 rounded-[2rem] shadow-xl">
                            <div class="flex items-center justify-between mb-6">
                                <h5 class="text-[11px] font-black text-white uppercase tracking-[0.2em] italic">Deep Scan: Internal Pages</h5>
                                <div class="flex space-x-2">
                                    <input type="text" wire:model="sub_page_url" placeholder="Paste internal URL (e.g /pricing)..." class="px-4 py-2 bg-slate-800 border-none rounded-xl text-[10px] text-white w-64 focus:ring-2 focus:ring-indigo-500 outline-none">
                                    <button wire:click="scanSubPage('{{ $competitor->id }}')" class="bg-indigo-600 text-white px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-500 transition shadow-lg shadow-indigo-500/20">Analyze</button>
                                </div>
                            </div>
                            <div class="space-y-3">
                                @forelse($competitor->pages as $page)
                                    <div class="flex items-center justify-between bg-slate-800/50 p-4 rounded-2xl border border-slate-700/50 hover:border-indigo-500 transition group">
                                        <span class="text-[11px] font-bold text-slate-300 truncate italic">🔗 {{ $page->url }}</span>
                                        <div class="flex items-center space-x-6">
                                            <span class="text-[9px] font-black text-slate-500 uppercase">P: <b class="text-white">{{ data_get($page->metadata, 'seo_report.stats.paragraphs', 0) }}</b></span>
                                            <span class="text-[9px] font-black text-slate-500 uppercase">H1: <b class="text-white">{{ data_get($page->metadata, 'seo_report.tags.h1_count', 0) }}</b></span>
                                            <span class="px-2 py-1 bg-indigo-500/10 text-indigo-400 text-[8px] font-black rounded uppercase">{{ $page->status }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center text-[10px] text-slate-500 italic uppercase tracking-widest">No internal pages audited yet</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            @if($competitor->status == 'fetching_completed')
                                <button wire:click="runAI('{{ $competitor->id }}')" class="px-8 py-3 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 shadow-xl shadow-indigo-200 transition">
                                    Generate AI Strategy
                                </button>
                            @elseif($competitor->status == 'completed')
                                <button wire:click="openAnalysis('{{ $competitor->id }}')" class="px-8 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-black shadow-xl transition">
                                    View Full Agency Report
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white border-4 border-dashed border-slate-100 rounded-[4rem] py-32 text-center shadow-inner">
                        <p class="text-slate-400 font-black uppercase tracking-widest italic">Waiting for surveillance targets...</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @if(!$project->is_verified)
        <div class="fixed inset-0 z-[999] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-md">
            <div class="bg-white w-full max-w-2xl rounded-[3.5rem] shadow-2xl border border-indigo-100 overflow-hidden transform transition-all scale-100">
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-inner ring-4 ring-indigo-50">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    
                    <h2 class="text-4xl font-black text-slate-900 uppercase tracking-tighter mb-4 italic">Verify Site Access</h2>
                    <p class="text-slate-500 font-medium mb-10 text-lg">Your project is locked. Add this meta tag to your site's <code class="text-indigo-600 font-black">&lt;head&gt;</code> to begin deep surveillance.</p>

                    <div class="bg-slate-50 p-8 rounded-[2.5rem] border border-slate-100 mb-10 relative group">
                        <div class="flex items-center justify-center">
                            <code class="text-sm text-slate-600 font-mono font-bold select-all" id="copy-text">
                                &lt;meta name="seostory-verify" content="{{ $project->verification_token }}"&gt;
                            </code>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4">
                        <button wire:click="verifySite" wire:loading.attr="disabled" class="w-full py-6 bg-indigo-600 text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-sm hover:bg-indigo-700 shadow-2xl shadow-indigo-200 transition-all active:scale-95">
                            <span wire:loading.remove>Verify Ownership Now</span>
                            <span wire:loading>Crawling Site... 🕷️</span>
                        </button>
                        <a href="/dashboard" class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] hover:text-slate-600 transition italic">Return to Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($showAnalysisModal)
        <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-xl z-[1000] flex items-center justify-center p-6">
            <div class="bg-white w-full max-w-5xl rounded-[4rem] shadow-2xl overflow-hidden max-h-[90vh] flex flex-col border border-indigo-100">
                <div class="p-10 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-3xl font-black text-slate-900 uppercase tracking-tighter italic">Strategic Intelligence Report</h2>
                    <button wire:click="$set('showAnalysisModal', false)" class="p-4 bg-slate-50 rounded-2xl hover:bg-rose-50 hover:text-rose-500 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-12 overflow-y-auto custom-scrollbar bg-white prose prose-indigo max-w-none">
                    {!! Str::markdown($activeAnalysis) !!}
                </div>
                <div class="p-10 bg-slate-50 border-t border-slate-100 flex justify-end">
                    <button wire:click="$set('showAnalysisModal', false)" class="px-10 py-4 bg-slate-900 text-white rounded-3xl font-black uppercase text-xs tracking-widest">Acknowledge Insights</button>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>