<div class="relative min-h-screen">
    {{-- Main Dashboard: Verified check ke sath --}}
    <div wire:poll.15s class="max-w-[1600px] mx-auto pb-20 transition-all duration-700 {{ !$project->is_verified ? 'filter blur-xl pointer-events-none select-none opacity-50' : '' }}">
        
        <nav class="flex mb-8 text-sm font-medium text-slate-400 space-x-2 px-4">
            <a href="/dashboard" class="hover:text-indigo-600 transition">Dashboard</a>
            <span>/</span>
            <span class="text-slate-900 font-bold underline decoration-indigo-500/30">Project: {{ $project->name }}</span>
        </nav>

        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6 px-4">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-none italic">{{ $project->name }}</h1>
                    <div class="flex items-center px-3 py-1 bg-indigo-600 rounded-full shadow-lg shadow-indigo-200">
                        <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse mr-2"></span>
                        <span class="text-[10px] text-white font-bold uppercase tracking-widest italic">SEO Postmortem Active</span>
                    </div>
                </div>
                <p class="text-slate-500 font-medium flex items-center group">
                    <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 10-5.656-5.656l-1.1 1.1"></path></svg>
                    <span class="border-b border-dotted border-slate-300 font-black text-indigo-600">{{ $project->url }}</span>
                </p>
            </div>
            
            <div class="flex space-x-4">
                <button wire:click="startSelfAudit" wire:loading.attr="disabled" class="px-8 py-4 bg-indigo-600 text-white rounded-[2rem] font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-indigo-200 hover:scale-105 transition-all flex items-center">
                    <span wire:loading.remove>⚡ Run Full Site Audit</span>
                    <span wire:loading class="flex items-center">
                        <svg class="animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Initializing...
                    </span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 px-4">
            
            <div class="lg:col-span-8 space-y-8">
                <div class="bg-white p-10 rounded-[3.5rem] border border-slate-100 shadow-xl shadow-slate-200/40">
                    <div class="flex items-center justify-between mb-10">
                        <div>
                            <h3 class="text-2xl font-black text-slate-900 tracking-tighter uppercase italic">Site Health Reports</h3>
                            <p class="text-slate-400 font-medium">Historical snapshots of your technical SEO progress.</p>
                        </div>
                        <div class="p-4 bg-indigo-50 rounded-2xl border border-indigo-100">
                             <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest block mb-1">Last Score</span>
                             <span class="text-2xl font-black text-indigo-700 leading-none">
                                {{ $audits->first()?->overall_health_score ?? '0' }}%
                             </span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        {{-- FIXED: Yahan controller se bheja gaya $audits variable use ho raha hai --}}
                        @forelse($audits as $audit)
                            <div class="group flex items-center justify-between p-6 bg-slate-50/50 rounded-[2.5rem] border border-transparent hover:border-indigo-100 hover:bg-white transition-all shadow-hover cursor-pointer">
                                <div class="flex items-center space-x-6">
                                    {{-- FIXED: Loop numbering logic --}}
                                    <div class="w-12 h-12 rounded-2xl bg-white border border-slate-100 flex items-center justify-center font-black text-xs text-slate-400 group-hover:text-indigo-600 transition">
                                        #{{ $audits->count() - $loop->index }}
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-black text-slate-800 tracking-tight">{{ $audit->created_at->format('M d, Y • h:i A') }}</h5>
                                        <div class="flex items-center space-x-3">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $audit->pages_scanned }} Pages Audited</p>
                                            {{-- Jahan History List mein status hai wahan ye change karein --}}
                                            @if($audit->status === 'processing')
                                                <button wire:click="openCrawlerStatus" class="flex items-center space-x-2 bg-amber-50 px-3 py-1 rounded-full border border-amber-100 group hover:bg-amber-100 transition">
                                                    <span class="w-2 h-2 bg-amber-500 rounded-full animate-ping"></span>
                                                    <span class="text-[9px] font-black text-amber-600 uppercase">Scanning Live...</span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-8">
                                    <div class="text-right">
                                        <span class="text-xl font-black {{ $audit->overall_health_score > 80 ? 'text-emerald-500' : 'text-amber-500' }}">
                                            {{ $audit->overall_health_score }}%
                                        </span>
                                        <p class="text-[8px] font-black text-slate-300 uppercase italic">Health Score</p>
                                    </div>
                                    <button wire:click="viewAudit({{ $audit->id }})" class="px-6 py-2 bg-slate-900 text-white text-[10px] font-black uppercase rounded-xl hover:bg-black transition">
                                        View Postmortem
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="py-20 text-center border-2 border-dashed border-slate-100 rounded-[3rem]">
                                <p class="text-slate-400 font-bold uppercase tracking-widest italic">No Deep Audits performed yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-8">
                <div class="bg-slate-900 p-8 rounded-[3.5rem] shadow-2xl sticky top-28">
                    <div class="flex items-center space-x-2 mb-8">
                        <div class="w-2 h-6 bg-indigo-500 rounded-full"></div>
                        <h3 class="text-xl font-black text-white uppercase italic tracking-tighter">Competitor Spy</h3>
                    </div>

                    <form wire:submit.prevent="addCompetitor" class="space-y-6 mb-10">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Target Brand</label>
                            <input type="text" wire:model="comp_name" class="w-full px-5 py-4 bg-slate-800 border-none rounded-2xl text-white font-semibold text-sm outline-none focus:ring-2 focus:ring-indigo-500" placeholder="e.g. Nike">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Main URL</label>
                            <input type="text" wire:model="comp_url" class="w-full px-5 py-4 bg-slate-800 border-none rounded-2xl text-white font-semibold text-sm outline-none focus:ring-2 focus:ring-indigo-500" placeholder="https://nike.com">
                        </div>
                        <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-2xl font-black uppercase text-[10px] tracking-[0.2em] hover:bg-indigo-700 transition shadow-lg">Deploy Tracker</button>
                    </form>

                    <div class="space-y-4">
                        @foreach($competitors as $competitor)
                            <div class="p-5 bg-slate-800/50 rounded-3xl border border-slate-700/50 hover:border-indigo-500 transition-all group">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="font-black text-white italic text-sm">{{ $competitor->name }}</h4>
                                    <span class="text-[8px] font-black text-indigo-400 uppercase tracking-widest">{{ $competitor->status }}</span>
                                </div>
                                <button wire:click="startCompetitorAudit('{{ $competitor->id }}')" class="w-full py-2 bg-slate-700 text-white text-[9px] font-black uppercase rounded-xl hover:bg-slate-600 transition tracking-widest">Run Intelligence</button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Verification Modal --}}
    @if(!$project->is_verified)
        <div class="fixed inset-0 z-[999] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-3xl">
            <div class="bg-white w-full max-w-2xl rounded-[4rem] shadow-2xl border border-indigo-100 overflow-hidden transform animate-in zoom-in duration-300">
                <div class="p-12 text-center">
                    <div class="w-24 h-24 bg-indigo-600 text-white rounded-[2.5rem] flex items-center justify-center mx-auto mb-10 shadow-2xl shadow-indigo-200 rotate-6">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    
                    <h2 class="text-5xl font-black text-slate-900 uppercase tracking-tighter mb-4 italic leading-none">Verification</h2>
                    <p class="text-slate-500 font-medium mb-10 text-lg leading-relaxed px-10">Add this meta tag to your <span class="text-indigo-600 font-bold">&lt;head&gt;</span> to unlock deep technical audits.</p>

                    <div class="bg-slate-50 p-8 rounded-[2.5rem] border-2 border-dashed border-indigo-200 mb-10 relative">
                        <code class="text-sm text-slate-600 font-mono font-bold select-all break-all" id="copy-text">
                            &lt;meta name="seostory-verify" content="{{ $project->verification_token }}"&gt;
                        </code>
                    </div>

                    @if (session()->has('error'))
                        <div class="mb-6 p-4 bg-rose-50 text-rose-600 text-xs font-black italic rounded-2xl flex items-center justify-center">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="flex flex-col gap-4">
                        <button wire:click="verifySite" wire:loading.attr="disabled" class="w-full py-6 bg-indigo-600 text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-sm hover:bg-indigo-700 shadow-2xl transition-all">
                            <span wire:loading.remove>Verify Ownership</span>
                            <span wire:loading>Crawling Headers...</span>
                        </button>
                        <a href="/dashboard" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] hover:text-slate-600 transition italic">Exit Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- AI Analysis Modal --}}
    @if($showAnalysisModal)
        <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-xl z-[1000] flex items-center justify-center p-6">
            <div class="bg-white w-full max-w-5xl rounded-[4rem] shadow-2xl overflow-hidden max-h-[90vh] flex flex-col border border-indigo-100">
                <div class="p-10 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-3xl font-black text-slate-900 uppercase tracking-tighter italic">Strategic Intelligence</h2>
                    <button wire:click="$set('showAnalysisModal', false)" class="p-4 bg-slate-50 rounded-2xl hover:bg-rose-50 hover:text-rose-500 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-12 overflow-y-auto custom-scrollbar bg-white prose prose-indigo max-w-none">
                    {!! Str::markdown($activeAnalysis) !!}
                </div>
            </div>
        </div>
    @endif
    @if($showAuditModal && $selectedAudit)
        <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-xl z-[1000] flex items-center justify-center p-6">
            <div class="bg-white w-full max-w-6xl rounded-[4rem] shadow-2xl overflow-hidden max-h-[90vh] flex flex-col border border-indigo-100">
                
                <div class="p-10 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <h2 class="text-3xl font-black text-slate-900 uppercase tracking-tighter italic">Audit Postmortem: #{{ $selectedAudit->id }}</h2>
                        <p class="text-slate-500 font-bold text-xs uppercase tracking-widest">{{ $selectedAudit->created_at->format('M d, Y') }} — {{ $selectedAudit->pages_scanned }} Pages Analyzed</p>
                    </div>
                    <button wire:click="$set('showAuditModal', false)" class="p-4 bg-white shadow-sm rounded-2xl hover:bg-rose-50 hover:text-rose-500 transition border border-slate-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-8 overflow-y-auto custom-scrollbar flex-1">
                    <table class="w-full text-left border-separate border-spacing-y-3">
                        <thead>
                            <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                <th class="px-6 pb-4">Page URL</th>
                                <th class="px-6 pb-4">Status</th>
                                <th class="px-6 pb-4">Load Time</th>
                                <th class="px-6 pb-4">Health</th>
                                <th class="px-6 pb-4">Issues</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($selectedAudit->projectPages as $page)
                                @php $data = json_decode($page->full_audit_data, true); @endphp
                                
                                <tr class="bg-slate-50/50 rounded-2xl hover:bg-slate-100 transition-all">
                                    <td class="px-6 py-4 cursor-pointer group" wire:click="inspectPage({{ $page->id }})">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-black text-indigo-600 group-hover:underline truncate max-w-xs">{{ $page->url }}</span>
                                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">{{ $page->title ?? 'No Title' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $page->status == 'audited' ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-200 text-slate-500' }}">
                                            {{ $page->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-xs font-black {{ $page->load_time > 2 ? 'text-rose-500' : 'text-slate-700' }}">
                                            {{ $page->load_time ?? '0.00' }}s
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-[10px] font-black">{{ $page->health_score }}%</span>
                                            <div class="h-1.5 w-12 bg-slate-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-indigo-500" style="width: {{ $page->health_score }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{-- Issues Detail Section --}}
                                        <div class="flex flex-col gap-1">
                                            @if(isset($data['issues']) && count($data['issues']) > 0)
                                                @foreach($data['issues'] as $issue)
                                                    <div class="flex items-center text-[9px] font-bold py-1 px-2 rounded-lg {{ $issue['type'] == 'critical' ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600' }}">
                                                        <span class="mr-1">⚠️</span> {{ $issue['msg'] }}
                                                    </div>
                                                @endforeach
                                            @else
                                                <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest">✅ Perfect SEO</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-10 bg-slate-50 border-t border-slate-100 flex justify-end">
                    <button wire:click="$set('showAuditModal', false)" class="px-10 py-4 bg-slate-900 text-white rounded-3xl font-black uppercase text-xs tracking-widest">Close Postmortem</button>
                </div>
            </div>
        </div>
    @endif
    @if($showPageDetailModal && $activePageData)
        <div class="fixed inset-0 bg-slate-900/95 backdrop-blur-2xl z-[1100] flex items-center justify-center p-4">
            <div class="bg-white w-full max-w-4xl rounded-[3rem] shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
                
                <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-indigo-600 text-white">
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tighter italic">Page Deep Dive</h3>
                        <p class="text-indigo-100 text-xs font-medium">{{ $activePageData['summary']['url'] }}</p>
                    </div>
                    <button wire:click="$set('showPageDetailModal', false)" class="text-white hover:rotate-90 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-8 overflow-y-auto custom-scrollbar space-y-8">
                    
                    <div class="grid grid-cols-4 gap-4">
                        <div class="p-4 bg-slate-50 rounded-3xl border border-slate-100 text-center">
                            <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Health</p>
                            <p class="text-xl font-black text-indigo-600">{{ $activePageData['summary']['health'] }}%</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-3xl border border-slate-100 text-center">
                            <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Words</p>
                            <p class="text-xl font-black text-slate-800">{{ $activePageData['content']['word_count'] ?? 0 }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-3xl border border-slate-100 text-center">
                            <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Internal Links</p>
                            <p class="text-xl font-black text-slate-800">{{ $activePageData['content']['internal_links'] ?? 0 }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-3xl border border-slate-100 text-center">
                            <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Images</p>
                            <p class="text-xl font-black text-slate-800">{{ $activePageData['content']['images_total'] ?? 0 }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <div class="space-y-4">
                            <h4 class="text-xs font-black uppercase text-slate-400 border-b pb-2 tracking-widest">SEO Meta Tags</h4>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase">Title</p>
                                    <p class="text-xs font-bold text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-100">{{ $activePageData['seo']['title'] ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase">Description</p>
                                    <p class="text-xs font-bold text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-100">{{ $activePageData['seo']['description'] ?? 'Missing meta description!' }}</p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase">Canonical</p>
                                    <p class="text-[10px] font-mono text-indigo-600 truncate">{{ $activePageData['seo']['canonical'] ?? 'No canonical tag set' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h4 class="text-xs font-black uppercase text-slate-400 border-b pb-2 tracking-widest">Heading Architecture</h4>
                            <div class="max-h-48 overflow-y-auto space-y-2 pr-2">
                                @if(!empty($activePageData['content']['h1']))
                                    @foreach($activePageData['content']['h1'] as $h1)
                                        <div class="p-2 bg-indigo-50 border border-indigo-100 rounded-lg text-xs font-bold text-indigo-700">H1: {{ $h1 }}</div>
                                    @endforeach
                                @endif
                                @if(!empty($activePageData['content']['h2']))
                                    @foreach($activePageData['content']['h2'] as $h2)
                                        <div class="p-2 bg-slate-50 border border-slate-100 rounded-lg text-xs font-medium text-slate-600">H2: {{ $h2 }}</div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="p-6 bg-slate-900 rounded-[2.5rem] text-white">
                        <div class="grid grid-cols-2 gap-8">
                            <div>
                                <h4 class="text-[10px] font-black uppercase text-slate-500 mb-4 tracking-widest">Technical Payload</h4>
                                <ul class="space-y-2 text-[11px] font-medium">
                                    <li class="flex justify-between"><span>Server:</span> <span class="text-indigo-400 font-bold">{{ $activePageData['technical']['server'] ?? 'Unknown' }}</span></li>
                                    <li class="flex justify-between"><span>Compression:</span> <span class="text-indigo-400 font-bold">{{ ($activePageData['technical']['is_compressed'] ?? false) ? 'GZIP/Brotli Active' : 'None' }}</span></li>
                                    <li class="flex justify-between"><span>Status Code:</span> <span class="text-emerald-400 font-bold">200 OK</span></li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="text-[10px] font-black uppercase text-slate-500 mb-4 tracking-widest">Schema Markup</h4>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($activePageData['schemas'] ?? [] as $schema)
                                        <span class="px-2 py-1 bg-slate-800 border border-slate-700 rounded-lg text-[10px] font-black text-indigo-300 italic">{{ is_array($schema) ? ($schema['@type'] ?? 'Item') : $schema }}</span>
                                    @empty
                                        <span class="text-[10px] text-slate-500 italic">No Schema Detected</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                
                <div class="p-6 border-t border-slate-100 flex justify-center bg-slate-50">
                    <button wire:click="$set('showPageDetailModal', false)" class="px-8 py-3 bg-slate-900 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-xl">Back to Overview</button>
                </div>
            </div>
        </div>
    @endif
</div>