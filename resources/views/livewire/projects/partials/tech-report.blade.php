@php
    $currentTech = json_decode($current->tech_meta_data ?? '{}', true);
    $prevTech = json_decode($prev->tech_meta_data ?? '{}', true);
@endphp

<div class="space-y-10">
    {{-- Main Score & Distribution --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1 bg-white p-10 rounded-[3rem] shadow-xl border-t-8 border-indigo-600">
            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Degree of Optimization</h4>
            <div class="text-7xl font-black text-slate-900 italic">{{ $current->score_tech }}%</div>
            <div class="mt-6 flex items-center gap-2">
                @if($prev)
                    @php $diff = $current->score_tech - $prev->score_tech; @endphp
                    <span class="text-sm font-bold {{ $diff >= 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                        {{ $diff >= 0 ? '+' : '' }}{{ $diff }}% since last crawl
                    </span>
                @endif
            </div>
        </div>

        <div class="lg:col-span-2 bg-slate-900 p-10 rounded-[3rem] text-white shadow-2xl">
            <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-8 italic">Response Times (HTML Download)</h4>
            <div class="space-y-6">
                @foreach(['Short (fast)' => 'fast', 'Medium' => 'medium', 'Long' => 'slow'] as $label => $key)
                    @php 
                        $val = $currentTech['distribution'][$key] ?? 0;
                        $total = $current->pages_scanned ?: 1;
                        $pct = ($val / $total) * 100;
                    @endphp
                    <div class="space-y-2">
                        <div class="flex justify-between text-[10px] font-bold uppercase italic">
                            <span>{{ $label }}</span>
                            <span>{{ $val }} Pages</span>
                        </div>
                        <div class="h-2 w-full bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-500 shadow-[0_0_15px_rgba(99,102,241,0.5)]" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Detailed Analysis Tables --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        {{-- About Crawling --}}
        <div class="bg-white rounded-[2.5rem] shadow-xl overflow-hidden border border-slate-100">
            <div class="px-8 py-5 bg-slate-50 border-b border-slate-100 font-black text-[10px] uppercase tracking-widest text-slate-500 italic">About Crawling</div>
            <table class="w-full text-left text-sm">
                <tbody class="divide-y divide-slate-50">
                    @include('livewire.projects.partials.seobility-row', ['label' => 'Pages accessed', 'curr' => $currentTech['crawling']['pages_accessed'] ?? 0, 'old' => $prevTech['crawling']['pages_accessed'] ?? 0])
                    @include('livewire.projects.partials.seobility-row', ['label' => 'External sites found', 'curr' => $currentTech['crawling']['external_sites'] ?? 0, 'old' => $prevTech['crawling']['external_sites'] ?? 0])
                </tbody>
            </table>
        </div>

        {{-- Meta Information --}}
        <div class="bg-white rounded-[2.5rem] shadow-xl overflow-hidden border border-slate-100">
            <div class="px-8 py-5 bg-slate-50 border-b border-slate-100 font-black text-[10px] uppercase tracking-widest text-slate-500 italic">Meta Information</div>
            <table class="w-full text-left text-sm">
                <tbody class="divide-y divide-slate-50">
                    @include('livewire.projects.partials.seobility-row', ['label' => 'Duplicate page titles', 'curr' => $currentTech['meta']['duplicate_titles'] ?? 0, 'old' => $prevTech['meta']['duplicate_titles'] ?? 0])
                    @include('livewire.projects.partials.seobility-row', ['label' => 'Problematic H1 tags', 'curr' => $currentTech['meta']['problematic_h1'] ?? 0, 'old' => $prevTech['meta']['problematic_h1'] ?? 0])
                </tbody>
            </table>
        </div>
    </div>
</div>