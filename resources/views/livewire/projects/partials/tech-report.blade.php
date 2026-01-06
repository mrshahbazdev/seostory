@php
    $data = json_decode($current->tech_meta_data ?? '{}', true);
    $old = json_decode($prev->tech_meta_data ?? '{}', true);
@endphp

<div class="space-y-10 animate-fade-in">
    {{-- Section 1: Dashboard Top Widgets --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Progress Chart (Simplified version of temporal development) --}}
        <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-slate-100 lg:col-span-1">
            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 italic">Development (Last 10 Audits)</h4>
            <div class="h-32 flex items-end gap-2 px-2">
                @foreach($audits->reverse() as $a)
                    <div class="flex-1 bg-indigo-100 rounded-t-xl hover:bg-indigo-600 transition-all cursor-pointer group relative" style="height: {{ $a->score_tech }}%">
                        <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[8px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap z-10">{{ $a->score_tech }}%</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Optimization Gauge --}}
        <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-slate-100 text-center">
            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 italic">Optimization Result</h4>
            <div class="relative inline-flex items-center justify-center">
                <svg class="w-32 h-32 transform -rotate-90">
                    <circle cx="64" cy="64" r="58" stroke="currentColor" stroke-width="12" fill="transparent" class="text-slate-100" />
                    <circle cx="64" cy="64" r="58" stroke="currentColor" stroke-width="12" fill="transparent" stroke-dasharray="364" stroke-dashoffset="{{ 364 - (364 * $current->score_tech / 100) }}" class="text-indigo-600 transition-all duration-1000" />
                </svg>
                <span class="absolute text-3xl font-black italic text-slate-900">{{ $current->score_tech }}%</span>
            </div>
            @if($prev)
                @php $diff = $current->score_tech - $prev->score_tech; @endphp
                <p class="text-[10px] font-black mt-3 {{ $diff >= 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                    {{ $diff >= 0 ? '▲' : '▼' }} {{ abs($diff) }}% since last crawl
                </p>
            @endif
        </div>

        {{-- Crawling Info --}}
        <div class="bg-white p-10 rounded-[3.5rem] shadow-xl border border-slate-100">
            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 italic border-b border-slate-50 pb-2">Crawling Stats</h4>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-slate-500 italic">Created on</span>
                    <span class="text-xs font-black text-slate-900">{{ $current->created_at->format('d.m.y H:i') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-slate-500 italic">Avg. Speed</span>
                    <span class="text-xs font-black text-indigo-600">{{ $data['crawling']['avg_response_time'] ?? '0.19' }} s</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-slate-500 italic">Crawl Count</span>
                    <span class="text-xs font-black text-slate-900">#{{ $current->id }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 2: Analysis Tables with CLICKABLE ROWS --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        
        {{-- About Crawling --}}
        <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden">
            <div class="px-8 py-5 bg-slate-50 border-b border-slate-100 flex items-center gap-3">
                <h3 class="text-[10px] font-black uppercase tracking-widest text-slate-600 italic">About Crawling</h3>
            </div>
            <table class="w-full text-left text-sm">
                <tbody class="divide-y divide-slate-50">
                    @include('livewire.projects.partials.seobility-row', ['slug' => 'pages_accessed', 'label' => 'Pages accessed', 'curr' => $data['crawling']['accessed'] ?? 0, 'old' => $old['crawling']['accessed'] ?? 0])
                    @include('livewire.projects.partials.seobility-row', ['slug' => 'relevant_pages', 'label' => 'Relevant Pages', 'curr' => $data['crawling']['relevant'] ?? 0, 'old' => $old['crawling']['relevant'] ?? 0])
                    @include('livewire.projects.partials.seobility-row', ['slug' => 'external_sites', 'label' => 'External sites found', 'curr' => $data['crawling']['external'] ?? 0, 'old' => $old['crawling']['external'] ?? 0])
                </tbody>
            </table>
        </div>

        {{-- Meta Information --}}
        <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden">
            <div class="px-8 py-5 bg-slate-50 border-b border-slate-100 flex items-center gap-3">
                <h3 class="text-[10px] font-black uppercase tracking-widest text-slate-600 italic">Meta Information</h3>
            </div>
            <table class="w-full text-left text-sm">
                <tbody class="divide-y divide-slate-50">
                    @include('livewire.projects.partials.seobility-row', ['slug' => 'duplicate_titles', 'label' => 'Duplicate page titles', 'curr' => $data['meta']['duplicate_titles'] ?? 0, 'old' => $old['meta']['duplicate_titles'] ?? 0, 'danger' => true])
                    @include('livewire.projects.partials.seobility-row', ['slug' => 'duplicate_descriptions', 'label' => 'Duplicate meta descriptions', 'curr' => $data['meta']['duplicate_descriptions'] ?? 0, 'old' => $old['meta']['duplicate_descriptions'] ?? 0, 'danger' => true])
                </tbody>
            </table>
        </div>

        {{-- Page Optimization --}}
        <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden">
            <div class="px-8 py-5 bg-slate-50 border-b border-slate-100 flex items-center gap-3">
                <h3 class="text-[10px] font-black uppercase tracking-widest text-slate-600 italic">Page Optimization</h3>
            </div>
            <table class="w-full text-left text-sm">
                <tbody class="divide-y divide-slate-50">
                    @include('livewire.projects.partials.seobility-row', ['slug' => 'problematic_h1', 'label' => 'Problems with H1 headings', 'curr' => $data['optimization']['h1_problems'] ?? 0, 'old' => $old['optimization']['h1_problems'] ?? 0, 'warning' => true])
                    @include('livewire.projects.partials.seobility-row', ['slug' => 'missing_alt', 'label' => 'Missing alt attributes', 'curr' => $data['optimization']['missing_alt'] ?? 0, 'old' => $old['optimization']['missing_alt'] ?? 0, 'warning' => true])
                </tbody>
            </table>
        </div>

        {{-- Response Time Graph --}}
        <div class="bg-slate-900 p-10 rounded-[3rem] text-white shadow-2xl">
            <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-10 italic flex items-center gap-2 border-b border-slate-800 pb-4">
                Distribution of response times
            </h4>
            <div class="space-y-8">
                @foreach(['Short (fast)' => 'fast', 'Medium' => 'medium', 'Long' => 'slow'] as $label => $key)
                    @php 
                        $val = $data['distribution'][$key] ?? 0;
                        $pct = ($val / ($current->pages_scanned ?: 1)) * 100;
                    @endphp
                    <div class="space-y-2 cursor-pointer group" wire:click="showIssueDetails('{{$key}}_pages')">
                        <div class="flex justify-between text-[10px] font-bold uppercase italic transition group-hover:text-indigo-400">
                            <span>{{ $label }}</span>
                            <span>{{ $val }} Pages</span>
                        </div>
                        <div class="h-2 w-full bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-500 transition-all duration-1000 shadow-[0_0_10px_rgba(99,102,241,0.5)]" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- --- 🔍 DRILL-DOWN ISSUE EXPLORER (The Matrix List) --- --}}
    @if($activeIssueFilter)
    <div class="mt-12 animate-in slide-in-from-bottom duration-500 px-4" id="issue-explorer">
        <div class="bg-white rounded-[3.5rem] shadow-[0_30px_60px_-15px_rgba(0,0,0,0.1)] border border-indigo-100 overflow-hidden">
            <div class="px-12 py-10 border-b border-slate-100 bg-indigo-50/30 flex justify-between items-center">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-3 h-3 bg-indigo-600 rounded-full animate-ping"></span>
                        <h4 class="text-2xl font-black text-slate-900 italic uppercase tracking-tighter">Analysis Node Explorer</h4>
                    </div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest italic">Filtering by: <span class="text-indigo-600">{{ str_replace('_', ' ', $activeIssueFilter) }}</span></p>
                </div>
                <button wire:click="resetDrillDown" class="p-5 bg-white rounded-3xl hover:bg-rose-50 hover:text-rose-500 transition shadow-sm border border-slate-100 group">
                    <svg class="w-6 h-6 transform group-hover:rotate-90 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="p-10 overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-4">
                    <thead>
                        <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic">
                            <th class="px-8 pb-4">URL Analysis Node</th>
                            <th class="px-8 pb-4">Status</th>
                            <th class="px-8 pb-4">Metric</th>
                            <th class="px-8 pb-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($filteredPages as $page)
                        <tr class="bg-slate-50/50 hover:bg-indigo-50/30 transition-all rounded-[2rem] group">
                            <td class="px-8 py-6 rounded-l-[2.5rem]">
                                <p class="text-sm font-black text-slate-800 italic group-hover:text-indigo-600 transition">{{ $page->url }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase mt-1 truncate max-w-lg">{{ $page->title ?? 'Untitled Metadata' }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase rounded-lg">CRAWLED_OK</span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-xs font-black text-slate-700">{{ $page->load_time }}s</span>
                            </td>
                            <td class="px-8 py-6 text-right rounded-r-[2.5rem]">
                                <button wire:click="inspectPage({{ $page->id }})" class="px-6 py-2 bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest rounded-xl hover:scale-105 transition">Full X-Ray</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-20 text-center text-slate-400 font-black italic uppercase tracking-widest">No matching records for this filter.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>