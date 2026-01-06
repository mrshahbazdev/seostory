<div class="min-h-screen bg-gray-50/50">
    {{-- Main Dashboard Content --}}
    <div wire:poll.15s class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 {{ !$project->is_verified ? 'blur-sm pointer-events-none opacity-50' : '' }}">
        
        {{-- Navigation & Header --}}
        <nav class="flex items-center space-x-2 mb-6">
            <a href="/dashboard" class="text-sm font-medium text-gray-500 hover:text-indigo-600">Dashboard</a>
            <svg class="h-4 w-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" /></svg>
            <span class="text-sm font-semibold text-gray-900">Project: {{ $project->name }}</span>
        </nav>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 p-8 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-4 mb-2">
                        <h1 class="text-4xl font-black text-gray-900 tracking-tight italic">{{ $project->name }}</h1>
                        <span class="px-3 py-1 bg-indigo-600 text-[10px] font-bold text-white rounded-full uppercase tracking-widest animate-pulse">Live Monitoring</span>
                    </div>
                    <p class="text-gray-500 font-medium flex items-center">
                        <span class="text-indigo-600 mr-2 font-bold underline">{{ $project->url }}</span>
                    </p>
                </div>
                
                <div class="flex gap-3">
                    <button wire:click="startSelfAudit" wire:loading.attr="disabled"
                        class="px-8 py-4 bg-indigo-600 text-white font-black text-xs uppercase tracking-widest rounded-2xl hover:bg-indigo-700 transition shadow-xl shadow-indigo-100 flex items-center">
                        <svg wire:loading.remove class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        <span wire:loading.remove>Run Deep Audit</span>
                        <span wire:loading class="flex items-center">
                            <svg class="animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Crawling...
                        </span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Seobility Style Pillar Cards --}}
        @php 
            $current = $audits->first(); 
            $prev = $audits->skip(1)->first();
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-8 rounded-[2.5rem] border-t-8 border-indigo-500 shadow-sm">
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Technology & Meta</h4>
                <div class="flex items-end justify-between">
                    <span class="text-5xl font-black text-indigo-600 leading-none">{{ $current->score_tech ?? 0 }}%</span>
                    @if($prev)
                        @php $diff = ($current->score_tech ?? 0) - ($prev->score_tech ?? 0); @endphp
                        <span class="text-xs font-bold {{ $diff >= 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                            {{ $diff >= 0 ? '▲' : '▼' }} {{ abs($diff) }}%
                        </span>
                    @endif
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border-t-8 border-emerald-500 shadow-sm">
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Structure</h4>
                <div class="flex items-end justify-between">
                    <span class="text-5xl font-black text-emerald-600 leading-none">{{ $current->score_structure ?? 0 }}%</span>
                    @if($prev)
                        @php $diff = ($current->score_structure ?? 0) - ($prev->score_structure ?? 0); @endphp
                        <span class="text-xs font-bold {{ $diff >= 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                            {{ $diff >= 0 ? '▲' : '▼' }} {{ abs($diff) }}%
                        </span>
                    @endif
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border-t-8 border-amber-500 shadow-sm">
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Content</h4>
                <div class="flex items-end justify-between">
                    <span class="text-5xl font-black text-amber-600 leading-none">{{ $current->score_content ?? 0 }}%</span>
                    @if($prev)
                        @php $diff = ($current->score_content ?? 0) - ($prev->score_content ?? 0); @endphp
                        <span class="text-xs font-bold {{ $diff >= 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                            {{ $diff >= 0 ? '▲' : '▼' }} {{ abs($diff) }}%
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            {{-- Left Side: Reports Table --}}
            <div class="lg:col-span-8 space-y-8">
                <div class="bg-white rounded-[3rem] shadow-xl border border-gray-100 overflow-hidden">
                    <div class="px-10 py-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                        <div>
                            <h2 class="text-xl font-black text-gray-900 uppercase tracking-tighter italic">Audit History</h2>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Previous snapshots</p>
                        </div>
                    </div>
                    
                    <div class="p-10">
                        <div class="space-y-4">
                            @forelse($audits as $audit)
                                <div class="group bg-gray-50/50 hover:bg-white border border-transparent hover:border-indigo-100 hover:shadow-xl rounded-[2rem] p-6 transition-all duration-300">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-6">
                                            <div class="w-14 h-14 bg-white rounded-2xl border border-gray-100 flex items-center justify-center font-black text-gray-400 group-hover:text-indigo-600 shadow-sm transition">
                                                #{{ $audits->count() - $loop->index }}
                                            </div>
                                            <div>
                                                <h4 class="font-black text-gray-900">{{ $audit->created_at->format('M d, Y • h:i A') }}</h4>
                                                <div class="flex items-center space-x-3 mt-1">
                                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $audit->pages_scanned }} Pages Analyzed</span>
                                                    @if($audit->status === 'processing')
                                                        <span class="text-[8px] bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full font-black animate-pulse uppercase">Auditing...</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center space-x-10">
                                            <div class="text-right">
                                                <p class="text-2xl font-black {{ $audit->overall_health_score > 80 ? 'text-indigo-600' : 'text-amber-500' }}">
                                                    {{ $audit->overall_health_score }}%
                                                </p>
                                                <p class="text-[8px] font-black text-gray-300 uppercase tracking-widest">Health</p>
                                            </div>
                                            <button wire:click="viewAudit({{ $audit->id }})" 
                                                class="px-6 py-3 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:scale-105 transition">
                                                Open Report
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-20 border-2 border-dashed border-gray-100 rounded-[3rem]">
                                    <p class="text-gray-400 font-black uppercase tracking-widest italic">No surveillance data found.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Side: Competitor Spy --}}
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-gray-900 rounded-[3rem] shadow-2xl p-10 text-white sticky top-10">
                    <h3 class="text-xl font-black italic uppercase tracking-tighter mb-8 border-b border-gray-800 pb-4">Spy Engine</h3>

                    <form wire:submit.prevent="addCompetitor" class="space-y-6 mb-10">
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-3 ml-1">Target Identity</label>
                            <input type="text" wire:model="comp_name" 
                                class="w-full px-5 py-4 bg-gray-800 border-none rounded-2xl text-white font-semibold text-sm focus:ring-2 focus:ring-indigo-500"
                                placeholder="e.g., Competitor Name">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-3 ml-1">Digital Location (URL)</label>
                            <input type="text" wire:model="comp_url" 
                                class="w-full px-5 py-4 bg-gray-800 border-none rounded-2xl text-white font-semibold text-sm focus:ring-2 focus:ring-indigo-500"
                                placeholder="https://target.com">
                        </div>
                        <button type="submit" 
                            class="w-full py-5 bg-indigo-600 text-white font-black uppercase text-[10px] tracking-widest rounded-2xl hover:bg-indigo-700 transition shadow-lg">
                            Deploy Tracker 🚀
                        </button>
                    </form>

                    <div class="space-y-4">
                        @foreach($competitors as $competitor)
                            <div class="bg-gray-800/50 border border-gray-700 rounded-3xl p-5 group hover:border-indigo-500 transition">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="font-black italic text-sm">{{ $competitor->name }}</h4>
                                    <span class="text-[8px] font-black bg-indigo-500 px-2 py-1 rounded text-white uppercase">{{ $competitor->status }}</span>
                                </div>
                                <button wire:click="startCompetitorAudit('{{ $competitor->id }}')" 
                                    class="w-full py-2 bg-slate-700 hover:bg-indigo-600 text-[9px] font-black uppercase rounded-xl transition tracking-widest">
                                    Analyze Intelligence
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL: AUDIT POSTMORTEM (Seobility Style) --}}
    @if($showAuditModal && $selectedAudit)
        <div class="fixed inset-0 bg-gray-900/95 backdrop-blur-xl flex items-center justify-center p-6 z-[2000]">
            <div class="bg-white rounded-[4rem] shadow-2xl max-w-6xl w-full max-h-[90vh] flex flex-col overflow-hidden">
                <div class="px-10 py-8 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                    <div>
                        <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter italic">Technical Postmortem</h2>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mt-2">Audit Snapshot: {{ $selectedAudit->created_at->format('M d, Y') }}</p>
                    </div>
                    <button wire:click="$set('showAuditModal', false)" class="p-4 bg-white rounded-2xl hover:text-rose-500 transition shadow-sm border border-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                <div class="flex-1 overflow-y-auto p-10 custom-scrollbar">
                    <table class="w-full text-left border-separate border-spacing-y-4">
                        <thead>
                            <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                                <th class="px-6 pb-2">Page URL</th>
                                <th class="px-6 pb-2">Load Time</th>
                                <th class="px-6 pb-2">Health Score</th>
                                <th class="px-6 pb-2 text-right">Issues Log</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-transparent">
                            @if($selectedAudit->projectPages)
                                @foreach($selectedAudit->projectPages as $page)
                                    @php $data = json_decode($page->full_audit_data ?? '{}', true); @endphp
                                    <tr class="bg-gray-50/50 hover:bg-indigo-50/30 transition-all cursor-pointer group" wire:click="inspectPage({{ $page->id }})">
                                        <td class="px-6 py-5 rounded-l-[2rem]">
                                            <div class="text-xs font-black text-gray-700 truncate max-w-sm group-hover:text-indigo-600">{{ $page->url }}</div>
                                            <div class="text-[9px] font-bold text-gray-400 uppercase mt-1">{{ $page->title ?? 'No Identity Found' }}</div>
                                        </td>
                                        <td class="px-6 py-5">
                                            <span class="text-xs font-black {{ ($page->load_time ?? 0) > 1 ? 'text-rose-500' : 'text-emerald-500' }}">
                                                {{ $page->load_time ?? '0.00' }}s
                                            </span>
                                        </td>
                                        <td class="px-6 py-5">
                                            <div class="flex items-center space-x-3">
                                                <span class="text-xs font-black">{{ $page->health_score ?? '0' }}%</span>
                                                <div class="w-16 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                                    <div class="h-full bg-indigo-600 rounded-full" style="width: {{ $page->health_score ?? 0 }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 text-right rounded-r-[2rem]">
                                            @if(isset($data['issues']) && count($data['issues']) > 0)
                                                <span class="px-3 py-1 bg-rose-50 text-rose-600 text-[9px] font-black uppercase rounded-lg">
                                                    {{ count($data['issues']) }} Alerts Found
                                                </span>
                                            @else
                                                <span class="text-[9px] font-black text-emerald-500 uppercase italic tracking-widest">✅ Safe</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL: PAGE DEEP DIVE --}}
    @if($showPageDetailModal && $activePageData)
        <div class="fixed inset-0 bg-gray-900/98 backdrop-blur-2xl flex items-center justify-center p-6 z-[3000]">
            <div class="bg-white rounded-[4rem] shadow-2xl max-w-5xl w-full max-h-[90vh] flex flex-col overflow-hidden">
                <div class="px-10 py-8 border-b border-gray-50 flex items-center justify-between bg-indigo-600 text-white">
                    <div>
                        <h2 class="text-2xl font-black uppercase tracking-tighter italic leading-none">Intelligence Deep Dive</h2>
                        <p class="text-[10px] font-black text-indigo-100 uppercase tracking-widest mt-2">{{ $activePageData['summary']['url'] }}</p>
                    </div>
                    <button wire:click="$set('showPageDetailModal', false)" class="p-4 bg-indigo-500/20 rounded-2xl hover:rotate-90 transition shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                <div class="flex-1 overflow-y-auto p-12 custom-scrollbar space-y-12">
                    {{-- Stats Overview --}}
                    <div class="grid grid-cols-4 gap-6">
                        <div class="p-8 bg-gray-50 rounded-[2.5rem] border border-gray-100 text-center">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Word Count</p>
                            <p class="text-4xl font-black text-gray-900 leading-none">{{ $activePageData['content']['word_count'] ?? 0 }}</p>
                        </div>
                        <div class="p-8 bg-gray-50 rounded-[2.5rem] border border-gray-100 text-center">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Pillar: Tech</p>
                            <p class="text-4xl font-black text-indigo-600 leading-none">{{ $activePageData['pillar_scores']['tech'] ?? 0 }}%</p>
                        </div>
                        <div class="p-8 bg-gray-50 rounded-[2.5rem] border border-gray-100 text-center">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Pillar: Struct</p>
                            <p class="text-4xl font-black text-emerald-600 leading-none">{{ $activePageData['pillar_scores']['struct'] ?? 0 }}%</p>
                        </div>
                        <div class="p-8 bg-gray-50 rounded-[2.5rem] border border-gray-100 text-center">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Pillar: Content</p>
                            <p class="text-4xl font-black text-amber-600 leading-none">{{ $activePageData['pillar_scores']['content'] ?? 0 }}%</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        {{-- Meta Intel --}}
                        <div class="space-y-6">
                            <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.3em] border-b pb-4">Metadata Analysis</h3>
                            <div class="space-y-4">
                                <div class="bg-gray-50 p-6 rounded-3xl">
                                    <label class="text-[8px] font-black text-indigo-500 uppercase tracking-widest">Page Title</label>
                                    <p class="text-sm font-bold text-gray-800 mt-2 leading-relaxed">{{ $activePageData['seo']['title'] ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-gray-50 p-6 rounded-3xl">
                                    <label class="text-[8px] font-black text-indigo-500 uppercase tracking-widest">Meta Description</label>
                                    <p class="text-sm font-bold text-gray-800 mt-2 leading-relaxed">{{ $activePageData['seo']['description'] ?? 'Missing description!' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Alert Log --}}
                        <div class="space-y-6">
                            <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.3em] border-b pb-4">Anomaly Log</h3>
                            <div class="space-y-3">
                                @forelse($activePageData['issues'] ?? [] as $issue)
                                    <div class="flex items-center p-4 {{ $issue['type'] == 'critical' ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-700' }} rounded-2xl border border-transparent shadow-sm">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                        <span class="text-xs font-black uppercase tracking-tight">{{ $issue['msg'] }}</span>
                                    </div>
                                @empty
                                    <div class="p-8 bg-emerald-50 text-emerald-700 rounded-3xl text-center">
                                        <p class="text-sm font-black italic tracking-widest">✅ ZERO ANOMALIES DETECTED</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>