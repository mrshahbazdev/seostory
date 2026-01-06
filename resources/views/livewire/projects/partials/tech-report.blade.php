@php
    $data = json_decode($current->tech_meta_data ?? '{}', true);
    $old = json_decode($prev->tech_meta_data ?? '{}', true);
@endphp

<div class="space-y-10 animate-fade-in">
    {{-- Section 1: Dashboard Top Widgets --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Progress Chart (Simplified version of temporal development) --}}
        <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-slate-100 lg:col-span-1">
            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Development</h4>
            <div class="h-32 flex items-end gap-1">
                @foreach($audits->reverse() as $a)
                    <div class="flex-1 bg-indigo-100 rounded-t-lg hover:bg-indigo-600 transition-all cursor-pointer" style="height: {{ $a->score_tech }}%" title="{{ $a->score_tech }}%"></div>
                @endforeach
            </div>
        </div>

        {{-- Optimization Gauge --}}
        <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-slate-100 text-center">
            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Degree of Optimization</h4>
            <div class="relative inline-flex items-center justify-center">
                <svg class="w-32 h-32 transform -rotate-90">
                    <circle cx="64" cy="64" r="58" stroke="currentColor" stroke-width="12" fill="transparent" class="text-slate-100" />
                    <circle cx="64" cy="64" r="58" stroke="currentColor" stroke-width="12" fill="transparent" stroke-dasharray="364" stroke-dashoffset="{{ 364 - (364 * $current->score_tech / 100) }}" class="text-indigo-600 transition-all duration-1000" />
                </svg>
                <span class="absolute text-3xl font-black italic">{{ $current->score_tech }}%</span>
            </div>
        </div>

        {{-- Crawling Info --}}
        <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-slate-100">
            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 italic">Crawling Info</h4>
            <div class="space-y-3">
                <div class="flex justify-between text-xs font-bold">
                    <span class="text-slate-500">Response time</span>
                    <span class="text-slate-900">{{ $data['crawling']['avg_response_time'] ?? '0.19' }} s</span>
                </div>
                <div class="flex justify-between text-xs font-bold">
                    <span class="text-slate-500">Duration</span>
                    <span class="text-slate-900">22 sec</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 2: Analysis Tables (Screenshot 1 & 3 style) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        
        {{-- About Crawling --}}
        <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden">
            <div class="px-8 py-5 bg-slate-50 border-b border-slate-100 flex items-center gap-3">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                <h3 class="text-[10px] font-black uppercase tracking-widest text-slate-600">About Crawling</h3>
            </div>
            <table class="w-full text-left text-sm">
                <tbody class="divide-y divide-slate-50">
                    @include('livewire.projects.partials.seobility-row', ['label' => 'Pages accessed', 'curr' => $data['crawling']['accessed'] ?? 0, 'old' => $old['crawling']['accessed'] ?? 0])
                    @include('livewire.projects.partials.seobility-row', ['label' => 'Internal pages found', 'curr' => $data['crawling']['internal'] ?? 0, 'old' => $old['crawling']['internal'] ?? 0])
                    @include('livewire.projects.partials.seobility-row', ['label' => 'External sites found', 'curr' => $data['crawling']['external'] ?? 0, 'old' => $old['crawling']['external'] ?? 0])
                </tbody>
            </table>
        </div>

        {{-- Meta Information --}}
        <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden">
            <div class="px-8 py-5 bg-slate-50 border-b border-slate-100 flex items-center gap-3">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M13 7h.01M13 11h.01M13 15h.01M17 7h.01M17 11h.01M17 15h.01" /></svg>
                <h3 class="text-[10px] font-black uppercase tracking-widest text-slate-600">Meta Information</h3>
            </div>
            <table class="w-full text-left text-sm">
                <tbody class="divide-y divide-slate-50">
                    @include('livewire.projects.partials.seobility-row', ['label' => 'Duplicate page titles', 'curr' => $data['meta']['duplicate_titles'] ?? 0, 'old' => $old['meta']['duplicate_titles'] ?? 0, 'danger' => true])
                    @include('livewire.projects.partials.seobility-row', ['label' => 'Duplicate meta descriptions', 'curr' => $data['meta']['duplicate_descriptions'] ?? 0, 'old' => $old['meta']['duplicate_descriptions'] ?? 0, 'danger' => true])
                </tbody>
            </table>
        </div>

        {{-- Page Optimization & Policies (Screenshot 1) --}}
        <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden">
            <div class="px-8 py-5 bg-slate-50 border-b border-slate-100 flex items-center gap-3">
                <span class="font-black text-slate-400">T</span>
                <h3 class="text-[10px] font-black uppercase tracking-widest text-slate-600">Page Optimization and Policies</h3>
            </div>
            <table class="w-full text-left text-sm">
                <tbody class="divide-y divide-slate-50">
                    @include('livewire.projects.partials.seobility-row', ['label' => 'Problems with H1 headings', 'curr' => $data['optimization']['h1_problems'] ?? 0, 'old' => $old['optimization']['h1_problems'] ?? 0, 'warning' => true])
                    @include('livewire.projects.partials.seobility-row', ['label' => 'Problems with strong and bold tags', 'curr' => $data['optimization']['bold_problems'] ?? 0, 'old' => $old['optimization']['bold_problems'] ?? 0, 'warning' => true])
                    @include('livewire.projects.partials.seobility-row', ['label' => 'Missing alt attributes', 'curr' => $data['optimization']['missing_alt'] ?? 0, 'old' => $old['optimization']['missing_alt'] ?? 0, 'warning' => true])
                </tbody>
            </table>
        </div>

        {{-- Response Time Graph (Screenshot 1 right side) --}}
        <div class="bg-slate-900 p-10 rounded-[3rem] text-white shadow-2xl">
            <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-10 italic flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Distribution of response times
            </h4>
            <div class="space-y-8">
                @foreach(['Short (fast)' => 'fast', 'Medium' => 'medium', 'Long' => 'slow'] as $label => $key)
                    @php 
                        $val = $data['distribution'][$key] ?? 0;
                        $pct = ($val / ($current->pages_scanned ?: 1)) * 100;
                    @endphp
                    <div class="space-y-2">
                        <div class="flex justify-between text-[10px] font-bold uppercase italic">
                            <span class="text-slate-400">{{ $label }}</span>
                            <span class="text-indigo-400">{{ $val }} Pages</span>
                        </div>
                        <div class="h-2 w-full bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-500 transition-all duration-1000" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>