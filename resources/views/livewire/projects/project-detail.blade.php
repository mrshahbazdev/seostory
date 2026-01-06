<div class="relative min-h-screen">
    {{-- Main Dashboard: Blur if not verified --}}
    <div wire:poll.5s class="max-w-[1600px] mx-auto pb-20 transition-all duration-700 {{ !$project->is_verified ? 'filter blur-xl pointer-events-none select-none opacity-50' : '' }}">
        
        <nav class="flex mb-8 text-sm font-medium text-slate-400 space-x-2 px-4">
            <a href="/dashboard" class="hover:text-indigo-600 transition">Dashboard</a>
            <span>/</span>
            <span class="text-slate-900 font-bold">Project: {{ $project->name }}</span>
        </nav>

        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6 px-4">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-none">{{ $project->name }}</h1>
                    <div class="flex items-center px-3 py-1 bg-indigo-600 rounded-full shadow-lg shadow-indigo-200">
                        <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse mr-2"></span>
                        <span class="text-[10px] text-white font-bold uppercase tracking-widest italic">Monitoring Active</span>
                    </div>
                </div>
                <p class="text-slate-500 font-medium flex items-center group">
                    <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 10-5.656-5.656l-1.1 1.1"></path></svg>
                    <span class="border-b border-dotted border-slate-300">{{ $project->url }}</span>
                </p>
            </div>
            
            <div class="flex space-x-4">
                <button class="px-6 py-3 bg-white border border-slate-200 rounded-2xl font-bold text-slate-700 hover:bg-slate-50 transition shadow-sm">Settings</button>
                <button wire:click="$set('showAddModal', true)" class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition shadow-xl">
                    + New Competitor
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-4 gap-10 px-4">
            <div class="xl:col-span-1">
                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-xl sticky top-28">
                    <h3 class="text-xl font-black text-slate-900 uppercase mb-6 italic tracking-tighter">Spy Engine</h3>
                    <form wire:submit.prevent="addCompetitor" class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Brand Name</label>
                            <input type="text" wire:model="comp_name" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:ring-2 focus:ring-indigo-500 font-semibold text-sm outline-none" placeholder="Nike">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Target URL</label>
                            <input type="text" wire:model="comp_url" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:ring-2 focus:ring-indigo-500 font-semibold text-sm outline-none" placeholder="https://nike.com">
                        </div>
                        <button type="submit" class="w-full py-5 bg-slate-900 text-white rounded-2xl font-bold hover:bg-black transition transform hover:-translate-y-1">
                            Deploy Tracker 🚀
                        </button>
                    </form>
                </div>
            </div>

            <div class="xl:col-span-3 space-y-8">
                @forelse($competitors as $competitor)
                    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8" wire:key="{{ $competitor->id }}">
                        {{-- Competitor UI (Already Perfect in your previous code) --}}
                        <div class="flex items-start justify-between mb-6">
                            <div class="flex items-center space-x-4">
                                <div class="w-14 h-14 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center font-black text-2xl text-indigo-600">
                                    {{ substr($competitor->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-slate-900">{{ $competitor->name }}</h4>
                                    <p class="text-xs text-indigo-500 font-bold">{{ $competitor->website_url }}</p>
                                </div>
                            </div>
                            <span class="px-4 py-1.5 bg-slate-800 text-white text-[10px] font-black uppercase rounded-full tracking-widest animate-pulse">
                                {{ $competitor->status }}
                            </span>
                        </div>
                        {{-- Stats Grid --}}
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 py-6 border-y border-slate-50 mb-6 bg-slate-50/50 rounded-3xl px-6">
                            <div class="text-center md:text-left">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Words</p>
                                <p class="text-lg font-black text-slate-800">{{ data_get($competitor->metadata, 'seo_report.stats.words', 0) }}</p>
                            </div>
                            <div class="text-center md:text-left border-l border-slate-200 pl-4">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">H1 / H2</p>
                                <p class="text-lg font-black text-slate-800">{{ data_get($competitor->metadata, 'seo_report.tags.h1_count', 0) }} / {{ data_get($competitor->metadata, 'seo_report.tags.h2_count', 0) }}</p>
                            </div>
                            <div class="text-center md:text-left border-l border-slate-200 pl-4">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Paragraphs</p>
                                <p class="text-lg font-black text-slate-800">{{ data_get($competitor->metadata, 'seo_report.stats.paragraphs', 0) }}</p>
                            </div>
                            <div class="text-center md:text-left border-l border-slate-200 pl-4">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-rose-500">Broken Alt</p>
                                <p class="text-lg font-black text-rose-600">{{ data_get($competitor->metadata, 'seo_report.images.missing_alt', 0) }}</p>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex justify-end space-x-3">
                            @if($competitor->status == 'fetching_completed')
                                <button wire:click="runAI('{{ $competitor->id }}')" class="px-6 py-2 bg-indigo-600 text-white text-[10px] font-black uppercase rounded-xl shadow-lg">Run AI Analysis</button>
                            @elseif($competitor->status == 'completed')
                                <button wire:click="openAnalysis('{{ $competitor->id }}')" class="px-6 py-2 bg-slate-900 text-white text-[10px] font-black uppercase rounded-xl">View Report</button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white border-2 border-dashed border-slate-200 rounded-[3rem] py-32 text-center shadow-inner">
                         <p class="text-slate-400 font-black uppercase tracking-widest italic">No surveillance targets found.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 🚀 VERIFICATION POPUP MODAL --}}
    @if(!$project->is_verified)
        <div class="fixed inset-0 z-[999] flex items-center justify-center p-6 bg-slate-900/40 backdrop-blur-xl">
            <div class="bg-white w-full max-w-2xl rounded-[3.5rem] shadow-[0_0_50px_rgba(79,70,229,0.3)] border border-indigo-100 overflow-hidden transform transition-all animate-in zoom-in duration-300">
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-indigo-600 text-white rounded-[2rem] flex items-center justify-center mx-auto mb-8 shadow-xl shadow-indigo-200 rotate-3">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    
                    <h2 class="text-4xl font-black text-slate-900 uppercase tracking-tighter mb-4 italic leading-none">Security Gate</h2>
                    <p class="text-slate-500 font-medium mb-10 text-lg leading-relaxed px-6">Your project is currently locked. To start auditing, please add this meta tag to your site's <span class="text-indigo-600 font-bold">&lt;head&gt;</span> section.</p>

                    <div class="bg-slate-50 p-8 rounded-[2.5rem] border border-slate-100 mb-10 relative group border-2 border-dashed border-indigo-200">
                        <div class="flex items-center justify-center">
                            <code class="text-[13px] text-slate-600 font-mono font-bold select-all break-all" id="copy-text">
                                &lt;meta name="seostory-verify" content="{{ $project->verification_token }}"&gt;
                            </code>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4">
                        @if (session()->has('error'))
                            <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center text-rose-600 text-xs font-bold italic">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                {{ session('error') }}
                            </div>
                        @endif
                        <button wire:click="verifySite" wire:loading.attr="disabled" class="w-full py-6 bg-indigo-600 text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-sm hover:bg-indigo-700 shadow-2xl shadow-indigo-200 transition-all active:scale-95 flex items-center justify-center">
                            <span wire:loading.remove>Verify Ownership</span>
                            <span wire:loading class="flex items-center">
                                <svg class="animate-spin h-5 w-5 mr-3 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Authenticating...
                            </span>
                        </button>
                        <a href="/dashboard" class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] hover:text-slate-600 transition italic">Exit to Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- AI Modal --}}
    @if($showAnalysisModal)
        <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-xl z-[1000] flex items-center justify-center p-6">
            <div class="bg-white w-full max-w-5xl rounded-[4rem] shadow-2xl overflow-hidden max-h-[90vh] flex flex-col border border-indigo-100">
                <div class="p-10 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-3xl font-black text-slate-900 uppercase tracking-tighter italic">Strategic Intelligence Report</h2>
                    <button wire:click="$set('showAnalysisModal', false)" class="p-4 bg-slate-50 rounded-2xl hover:bg-rose-50 hover:text-rose-500 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-12 overflow-y-auto custom-scrollbar bg-white">
                    <article class="prose prose-indigo max-w-none">
                        {!! Str::markdown($activeAnalysis) !!}
                    </article>
                </div>
                <div class="p-10 bg-slate-50 border-t border-slate-100 flex justify-end">
                    <button wire:click="$set('showAnalysisModal', false)" class="px-10 py-4 bg-slate-900 text-white rounded-3xl font-black uppercase text-xs tracking-widest">Close Intelligence Report</button>
                </div>
            </div>
        </div>
    @endif
</div>