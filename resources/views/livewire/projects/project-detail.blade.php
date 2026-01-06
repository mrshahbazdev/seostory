<div class="min-h-screen bg-[#f8fafc] font-sans antialiased text-slate-900">
    {{-- Top Verification Blur Overlay --}}
    @if(!$project->is_verified)
        <div class="fixed inset-0 z-[100] backdrop-blur-md bg-white/30 flex items-center justify-center p-6">
            <div class="bg-white rounded-[3rem] shadow-2xl border border-slate-200 max-w-2xl w-full p-12 text-center transform scale-100 transition-all">
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
        
        {{-- Header Section --}}
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
                    <span wire:loading class="flex items-center">
                        <svg class="animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Initializing Crawler...
                    </span>
                </button>
            </div>
        </header>

        {{-- Pillar Navigation (Seobility Style) --}}
        <div class="flex items-center gap-2 mb-10 bg-white p-2 rounded-[2.5rem] shadow-sm border border-slate-100 w-fit">
            <button wire:click="setView('overview')" class="px-8 py-4 rounded-[2rem] text-xs font-black uppercase tracking-widest transition-all {{ $currentView == 'overview' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50' }}">Overview</button>
            <button wire:click="setView('tech')" class="px-8 py-4 rounded-[2rem] text-xs font-black uppercase tracking-widest transition-all {{ $currentView == 'tech' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50' }}">Tech & Meta</button>
            <button wire:click="setView('structure')" class="px-8 py-4 rounded-[2rem] text-xs font-black uppercase tracking-widest transition-all {{ $currentView == 'structure' ? 'bg-emerald-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50' }}">Structure</button>
            <button wire:click="setView('content')" class="px-8 py-4 rounded-[2rem] text-xs font-black uppercase tracking-widest transition-all {{ $currentView == 'content' ? 'bg-amber-500 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-50' }}">Content</button>
        </div>

        {{-- View Rendering --}}
        @php 
            $current = $audits->first(); 
            $prev = $audits->skip(1)->first();
        @endphp

        @if($currentView == 'overview')
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 animate-fade-in">
                
                {{-- Left: Pillar Scores --}}
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

                    {{-- History List --}}
                    <div class="bg-white rounded-[4rem] shadow-xl border border-slate-100 overflow-hidden">
                        <div class="px-10 py-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                            <h3 class="text-xl font-black italic tracking-tighter uppercase">Audit Timeline</h3>
                        </div>
                        <div class="p-10 space-y-6">
                            @forelse($audits as $audit)
                                <div class="group bg-slate-50/50 hover:bg-white border border-transparent hover:border-indigo-100 rounded-[2.5rem] p-8 flex items-center justify-between transition-all duration-300">
                                    <div class="flex items-center gap-8">
                                        <div class="w-16 h-16 bg-white rounded-3xl border border-slate-100 flex items-center justify-center font-black text-slate-400 group-hover:text-indigo-600 shadow-sm transition">
                                            #{{ $audits->count() - $loop->index }}
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-black text-slate-900">{{ $audit->created_at->format('M d, Y • H:i') }}</h4>
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">{{ $audit->pages_scanned }} Pages Processed</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-12">
                                        <div class="text-right">
                                            <p class="text-3xl font-black italic text-slate-900">{{ $audit->overall_health_score }}%</p>
                                            <p class="text-[8px] font-black text-slate-300 uppercase tracking-widest">Global Health</p>
                                        </div>
                                        <button wire:click="viewAudit({{ $audit->id }})" class="px-8 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-600 transition">Analyze</button>
                                    </div>
                                </div>
                            @empty
                                <div class="py-20 text-center border-2 border-dashed border-slate-100 rounded-[3rem]">
                                    <p class="text-slate-400 font-black uppercase italic tracking-widest">No Postmortem Records Found.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Right: Spy Engine --}}
                <div class="lg:col-span-4">
                    <div class="bg-slate-900 rounded-[4rem] p-12 text-white shadow-2xl sticky top-10">
                        <h3 class="text-2xl font-black italic uppercase tracking-tighter mb-10 border-b border-slate-800 pb-6">Competitor Spy</h3>
                        <form wire:submit.prevent="addCompetitor" class="space-y-6 mb-12">
                            <input type="text" wire:model="comp_name" class="w-full px-6 py-5 bg-slate-800 border-none rounded-3xl text-white font-bold text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Brand Name">
                            <input type="text" wire:model="comp_url" class="w-full px-6 py-5 bg-slate-800 border-none rounded-3xl text-white font-bold text-sm focus:ring-2 focus:ring-indigo-500" placeholder="https://domain.com">
                            <button type="submit" class="w-full py-5 bg-indigo-600 text-white font-black uppercase text-xs tracking-[0.2em] rounded-3xl hover:bg-indigo-700 transition shadow-xl shadow-indigo-500/20">Launch Tracker</button>
                        </form>
                        
                        <div class="space-y-4">
                            @foreach($competitors as $competitor)
                                <div class="bg-white/5 border border-white/10 rounded-[2rem] p-6 hover:border-indigo-500 transition-all">
                                    <div class="flex justify-between items-center mb-4">
                                        <span class="font-black italic text-sm">{{ $competitor->name }}</span>
                                        <span class="text-[8px] font-black text-indigo-400 uppercase">{{ $competitor->status }}</span>
                                    </div>
                                    <button class="w-full py-3 bg-slate-800 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-indigo-600 transition">Intelligence Sync</button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Deep Report Views (Tech, Structure, Content) --}}
        @if(in_array($currentView, ['tech', 'structure', 'content']))
            <div class="animate-fade-in space-y-10">
                 @include('livewire.projects.partials.' . $currentView . '-report')
            </div>
        @endif
    </div>

    {{-- Postmortem Table Modal (Seobility Style) --}}
    @if($showAuditModal && $selectedAudit)
        <div class="fixed inset-0 bg-slate-900/95 backdrop-blur-xl z-[2000] flex items-center justify-center p-6">
            <div class="bg-white rounded-[4rem] shadow-2xl max-w-7xl w-full max-h-[90vh] flex flex-col overflow-hidden">
                <header class="p-12 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h2 class="text-4xl font-black text-slate-900 italic tracking-tighter uppercase leading-none">Full Postmortem</h2>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mt-3 italic">Audit Data for {{ $selectedAudit->created_at->format('M d, Y') }}</p>
                    </div>
                    <button wire:click="$set('showAuditModal', false)" class="w-16 h-16 bg-white rounded-3xl flex items-center justify-center text-slate-400 hover:text-rose-500 shadow-sm transition border border-slate-100">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </header>

                <div class="flex-1 overflow-y-auto p-12 custom-scrollbar">
                    <table class="w-full text-left border-separate border-spacing-y-4">
                        <thead>
                            <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                <th class="px-8 pb-4 italic">Analysis Node (URL)</th>
                                <th class="px-8 pb-4">Speed</th>
                                <th class="px-8 pb-4">Health Index</th>
                                <th class="px-8 pb-4 text-right">Anomalies</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($selectedAudit->projectPages as $page)
                                <tr class="bg-slate-50/50 hover:bg-white border border-transparent hover:border-indigo-100 transition-all rounded-[2rem] group cursor-pointer" wire:click="inspectPage({{ $page->id }})">
                                    <td class="px-8 py-6 rounded-l-[2rem]">
                                        <div class="text-sm font-black text-slate-800 truncate max-w-md group-hover:text-indigo-600 transition">{{ $page->url }}</div>
                                        <div class="text-[9px] font-bold text-slate-400 uppercase mt-1 italic">{{ $page->title ?? 'Untitled' }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="text-xs font-black {{ ($page->load_time ?? 0) > 1.5 ? 'text-rose-500' : 'text-emerald-500' }}">{{ $page->load_time ?? '0.00' }}s</span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3">
                                            <span class="text-xs font-black">{{ $page->health_score ?? 0 }}%</span>
                                            <div class="w-20 h-1.5 bg-slate-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-indigo-600" style="width: {{ $page->health_score ?? 0 }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-right rounded-r-[2rem]">
                                        @php $data = json_decode($page->full_audit_data ?? '{}', true); @endphp
                                        <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase {{ count($data['issues'] ?? []) > 0 ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }}">
                                            {{ count($data['issues'] ?? []) }} Issues
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>