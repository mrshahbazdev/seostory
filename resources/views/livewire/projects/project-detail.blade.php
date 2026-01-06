<div class="min-h-screen bg-[#f8fafc] font-sans antialiased text-slate-900">
    {{-- 1. Site Verification Overlay --}}
    @if(!$project->is_verified)
        <div class="fixed inset-0 z-[5000] backdrop-blur-md bg-white/30 flex items-center justify-center p-6">
            <div class="bg-white rounded-[3rem] shadow-2xl border border-slate-200 max-w-2xl w-full p-12 text-center transform scale-100 transition-all animate-in zoom-in duration-300">
                <div class="w-24 h-24 bg-indigo-600 rounded-[2rem] flex items-center justify-center mx-auto mb-8 shadow-xl rotate-3">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                </div>
                <h2 class="text-4xl font-black tracking-tighter mb-4 italic uppercase">Ownership Required</h2>
                <p class="text-slate-500 font-medium mb-8 leading-relaxed">Please add the following meta tag to your <span class="text-indigo-600 font-bold">&lt;head&gt;</span> to unlock deep technical analysis.</p>
                <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-3xl p-6 mb-8 font-mono text-sm text-slate-600 select-all">
                    &lt;meta name="seostory-verify" content="{{ $project->verification_token }}"&gt;
                </div>
                <button wire:click="verifySite" wire:loading.attr="disabled" class="w-full py-5 bg-indigo-600 text-white rounded-2xl font-black uppercase tracking-widest hover:bg-indigo-700 transition shadow-xl shadow-indigo-100">
                    Verify Now
                </button>
            </div>
        </div>
    @endif

    <div class="max-w-[1600px] mx-auto px-6 lg:px-12 py-10 {{ !$project->is_verified ? 'pointer-events-none' : '' }}">
        
        {{-- 2. Header Section --}}
        <header class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 mb-12">
            <div>
                <nav class="flex items-center space-x-2 text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">
                    <a href="/dashboard" class="hover:text-indigo-600 transition">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-900 italic">Project Detail</span>
                </nav>
                <div class="flex items-center gap-6">
                    <h1 class="text-5xl font-black tracking-tighter italic text-slate-900 leading-none">{{ $project->name }}</h1>
                    <div class="px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-2xl flex items-center shadow-sm">
                        <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse mr-2"></span>
                        <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">{{ $project->url }}</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button wire:click="startSelfAudit" wire:loading.attr="disabled" class="px-10 py-5 bg-indigo-600 text-white rounded-[2rem] font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-indigo-200 hover:scale-[1.02] active:scale-95 transition-all flex items-center">
                    <span wire:loading.remove>⚡ Run Site Postmortem</span>
                    <span wire:loading class="flex items-center italic uppercase tracking-widest">
                        <svg class="animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Deploying Bot...
                    </span>
                </button>
            </div>
        </header>

        {{-- 3. Pillar Navigation --}}
        <div class="flex items-center gap-2 mb-10 bg-white p-2 rounded-[2.5rem] shadow-sm border border-slate-100 w-fit">
            <button wire:click="setView('overview')" class="px-8 py-4 rounded-[2rem] text-xs font-black uppercase tracking-widest transition-all {{ $currentView == 'overview' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50' }}">Overview</button>
            <button wire:click="setView('tech')" class="px-8 py-4 rounded-[2rem] text-xs font-black uppercase tracking-widest transition-all {{ $currentView == 'tech' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50' }}">Tech & Meta</button>
            <button wire:click="setView('structure')" class="px-8 py-4 rounded-[2rem] text-xs font-black uppercase tracking-widest transition-all {{ $currentView == 'structure' ? 'bg-emerald-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50' }}">Structure</button>
            <button wire:click="setView('content')" class="px-8 py-4 rounded-[2rem] text-xs font-black uppercase tracking-widest transition-all {{ $currentView == 'content' ? 'bg-amber-500 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50' }}">Content</button>
        </div>

        {{-- 4. View Logic --}}
        @php 
            $current = $audits->first(); 
            $prev = $audits->skip(1)->first();
        @endphp

        @if($currentView == 'overview')
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 animate-fade-in">
                <div class="lg:col-span-8 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach(['Tech' => ['score' => $current->score_tech ?? 0, 'color' => 'indigo'], 
                                  'Structure' => ['score' => $current->score_structure ?? 0, 'color' => 'emerald'], 
                                  'Content' => ['score' => $current->score_content ?? 0, 'color' => 'amber']] as $name => $data)
                            <div class="bg-white p-10 rounded-[3.5rem] border-b-8 border-{{$data['color']}}-500 shadow-xl shadow-slate-200/50">
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">{{ $name }} Score</h4>
                                <div class="text-6xl font-black text-slate-900 leading-none italic">{{ $data['score'] }}%</div>
                            </div>
                        @endforeach
                    </div>

                    {{-- History Table --}}
                    <div class="bg-white rounded-[4rem] shadow-xl border border-slate-100 overflow-hidden">
                        <div class="p-10 space-y-6">
                            @forelse($audits as $audit)
                                <div class="group bg-slate-50/50 hover:bg-white border border-transparent hover:border-indigo-100 rounded-[2.5rem] p-8 flex items-center justify-between transition-all duration-300">
                                    <div class="flex items-center gap-8">
                                        <div class="w-16 h-16 bg-white rounded-3xl border border-slate-100 flex items-center justify-center font-black text-slate-400 group-hover:text-indigo-600 shadow-sm transition">#{{ $audits->count() - $loop->index }}</div>
                                        <div>
                                            <h4 class="text-lg font-black text-slate-900 italic">{{ $audit->created_at->format('M d, Y • H:i') }}</h4>
                                            <p class="text-[10px] font-black text-slate-400 uppercase mt-1 tracking-widest italic">{{ $audit->pages_scanned }} Nodes Analyzed</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-12">
                                        <div class="text-right">
                                            <p class="text-3xl font-black italic text-slate-900">{{ $audit->overall_health_score }}%</p>
                                            <p class="text-[8px] font-black text-slate-300 uppercase">Health Index</p>
                                        </div>
                                        <button wire:click="viewAudit({{ $audit->id }})" class="px-8 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-600 transition">Analyze</button>
                                    </div>
                                </div>
                            @empty
                                <div class="py-20 text-center text-slate-400 font-black italic uppercase tracking-widest">Initialization Required.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Competitor Spy --}}
                <div class="lg:col-span-4">
                    <div class="bg-slate-900 rounded-[4rem] p-12 text-white shadow-2xl sticky top-10">
                        <h3 class="text-2xl font-black italic uppercase tracking-tighter mb-10 border-b border-slate-800 pb-6">Competitor Spy</h3>
                        <form wire:submit.prevent="addCompetitor" class="space-y-6 mb-12">
                            <input type="text" wire:model="comp_name" class="w-full px-6 py-5 bg-slate-800 border-none rounded-3xl text-white font-bold text-sm" placeholder="Target Name">
                            <input type="text" wire:model="comp_url" class="w-full px-6 py-5 bg-slate-800 border-none rounded-3xl text-white font-bold text-sm" placeholder="https://">
                            <button type="submit" class="w-full py-5 bg-indigo-600 text-white font-black uppercase text-xs tracking-widest rounded-3xl shadow-xl shadow-indigo-500/10">Launch Deploy</button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        @if(in_array($currentView, ['tech', 'structure', 'content']))
            <div class="animate-fade-in space-y-10">
                 @include('livewire.projects.partials.' . $currentView . '-report')
            </div>
        @endif
    </div>

    {{-- MODAL 1: FULL POSTMORTEM (List of Pages) --}}
    @if($showAuditModal && $selectedAudit)
        <div class="fixed inset-0 bg-slate-900/95 backdrop-blur-xl z-[2000] flex items-center justify-center p-6">
            <div class="bg-white rounded-[4rem] shadow-2xl max-w-7xl w-full max-h-[90vh] flex flex-col overflow-hidden animate-in zoom-in duration-300">
                <header class="p-12 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h2 class="text-4xl font-black text-slate-900 italic tracking-tighter uppercase leading-none">Full Postmortem</h2>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mt-3 italic underline">Snapshot Data: {{ $selectedAudit->created_at->format('M d, Y') }}</p>
                    </div>
                    <button wire:click="$set('showAuditModal', false)" class="w-16 h-16 bg-white rounded-3xl flex items-center justify-center text-slate-400 hover:text-rose-500 shadow-sm transition border border-slate-100">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </header>

                <div class="flex-1 overflow-y-auto p-12 custom-scrollbar">
                    <table class="w-full text-left border-separate border-spacing-y-4">
                        <thead>
                            <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">
                                <th class="px-8 pb-4">URL Node (Click for Analysis)</th>
                                <th class="px-8 pb-4">Speed</th>
                                <th class="px-8 pb-4">Health Index</th>
                                <th class="px-8 pb-4 text-right">Debt Log</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($selectedAudit->projectPages as $page)
                                <tr class="bg-slate-50/50 hover:bg-indigo-50/30 transition-all rounded-[2.5rem] group cursor-pointer" 
                                    wire:key="page-{{ $page->id }}"
                                    wire:click="inspectPage({{ $page->id }})">
                                    <td class="px-8 py-8 rounded-l-[2.5rem]">
                                        <div class="text-sm font-black text-slate-800 italic group-hover:text-indigo-600 transition underline decoration-transparent group-hover:decoration-indigo-100">{{ $page->url }}</div>
                                        <div class="text-[9px] font-bold text-slate-400 uppercase mt-2 tracking-widest italic">{{ $page->title ?? 'NODE_UNDEFINED' }}</div>
                                    </td>
                                    <td class="px-8 py-8">
                                        <span class="text-xs font-black italic {{ ($page->load_time ?? 0) > 0.5 ? 'text-rose-500' : 'text-emerald-500' }} tracking-tighter">{{ $page->load_time ?? '0.00' }}s</span>
                                    </td>
                                    <td class="px-8 py-8">
                                        <div class="flex items-center gap-3">
                                            <span class="text-xs font-black italic">{{ $page->health_score ?? 0 }}%</span>
                                            <div class="w-20 h-1 bg-slate-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-indigo-600 shadow-[0_0_10px_rgba(79,70,229,0.4)]" style="width: {{ $page->health_score ?? 0 }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-8 text-right rounded-r-[2.5rem]">
                                        @php $data = json_decode($page->full_audit_data ?? '{}', true); @endphp
                                        <div class="flex items-center justify-end gap-3">
                                            <span class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase italic tracking-widest {{ count($data['issues'] ?? []) > 0 ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }}">
                                                {{ count($data['issues'] ?? []) }} ANOMALIES
                                            </span>
                                            <svg class="w-4 h-4 text-slate-300 group-hover:text-indigo-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" /></svg>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL 2: DEEP DIVE ANALYSIS (X-Ray of Single Page) --}}
    @if($showPageDetailModal && $activePageData)
        <div class="fixed inset-0 bg-slate-900/98 backdrop-blur-2xl z-[4000] flex items-center justify-center p-6">
            <div class="bg-white rounded-[4rem] shadow-2xl max-w-6xl w-full max-h-[92vh] flex flex-col overflow-hidden animate-in slide-in-from-bottom duration-500">
                <header class="px-10 py-10 border-b border-slate-50 flex items-center justify-between bg-indigo-600 text-white shadow-xl">
                    <div>
                        <h2 class="text-3xl font-black uppercase tracking-tighter italic leading-none">Intelligence Deep Dive</h2>
                        <p class="text-[10px] font-black text-indigo-100 uppercase tracking-widest mt-2 flex items-center italic">
                            <span class="w-2 h-2 bg-emerald-400 rounded-full mr-2 animate-pulse"></span>
                            Inspecting: {{ $activePageData['summary']['url'] }}
                        </p>
                    </div>
                    <button wire:click="$set('showPageDetailModal', false)" class="p-5 bg-indigo-500 rounded-3xl hover:bg-rose-500 transition shadow-sm hover:rotate-90">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </header>
                
                <div class="flex-1 overflow-y-auto p-12 custom-scrollbar space-y-12 bg-slate-50/50">
                    {{-- Key Metrics --}}
                    <div class="grid grid-cols-4 gap-8">
                        @foreach([
                            'Node Weight' => ($activePageData['content']['word_count'] ?? 0) . ' Words',
                            'Tech Index' => ($activePageData['pillar_scores']['tech'] ?? 0) . '%',
                            'Struct Index' => ($activePageData['pillar_scores']['struct'] ?? 0) . '%',
                            'Content Index' => ($activePageData['pillar_scores']['content'] ?? 0) . '%'
                        ] as $label => $val)
                            <div class="p-10 bg-white rounded-[3rem] shadow-xl shadow-slate-200/50 text-center border border-slate-50">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 italic">{{ $label }}</p>
                                <p class="text-4xl font-black text-slate-900 italic leading-none">{{ $val }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        {{-- Meta Discovery --}}
                        <div class="space-y-8">
                            <h3 class="text-xs font-black text-indigo-600 uppercase tracking-[0.4em] italic border-b border-indigo-100 pb-4">Metadata Node Analytics</h3>
                            <div class="space-y-6">
                                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 group">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-4 block italic">Target Title</label>
                                    <p class="text-sm font-black text-slate-800 italic leading-relaxed">{{ $activePageData['seo']['title'] ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 group">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-4 block italic">Target Description</label>
                                    <p class="text-sm font-bold text-slate-500 italic leading-relaxed italic">{{ $activePageData['seo']['description'] ?? 'NULL_DESCRIPTION_ERROR' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Issue Tracker --}}
                        <div class="space-y-8">
                            <h3 class="text-xs font-black text-rose-600 uppercase tracking-[0.4em] italic border-b border-rose-100 pb-4">Anomaly Radar Log</h3>
                            <div class="space-y-4">
                                @forelse($activePageData['issues'] ?? [] as $issue)
                                    <div class="flex items-center p-6 bg-rose-50 text-rose-700 rounded-[2rem] border border-rose-100 shadow-sm transition hover:scale-[1.02]">
                                        <div class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center mr-4 shadow-sm text-rose-500">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                        </div>
                                        <span class="text-xs font-black uppercase tracking-tight italic">{{ $issue['msg'] }}</span>
                                    </div>
                                @empty
                                    <div class="p-16 bg-emerald-50 rounded-[4rem] text-center border border-emerald-100">
                                        <p class="text-xl font-black italic tracking-[0.2em] text-emerald-700 leading-none">CLEAN_NODE_STATUS</p>
                                        <p class="text-[10px] font-black uppercase mt-4 text-emerald-600 opacity-60 tracking-widest">Surveillance complete • No errors</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <footer class="p-10 bg-white border-t border-slate-50 flex justify-center">
                    <button wire:click="$set('showPageDetailModal', false)" class="px-16 py-5 bg-slate-900 text-white rounded-3xl font-black text-[10px] uppercase tracking-[0.3em] hover:bg-indigo-600 transition shadow-2xl">Terminate Node Analysis</button>
                </footer>
            </div>
        </div>
    @endif
</div>