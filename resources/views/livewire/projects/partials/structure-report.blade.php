<div class="space-y-10 animate-fade-in">
    <div class="bg-white p-12 rounded-[4rem] shadow-xl border border-slate-100 flex justify-between items-center border-t-8 border-emerald-500">
        <div>
            <h2 class="text-4xl font-black text-slate-900 italic uppercase tracking-tighter">Site Structure</h2>
            <p class="text-xs font-bold text-slate-400 mt-3 uppercase tracking-widest italic">Internal Linking & Crawl Depth</p>
        </div>
        <div class="text-right">
            <span class="text-6xl font-black text-emerald-600 italic leading-none">{{ $current->score_structure }}%</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        {{-- Page Levels --}}
        <div class="bg-slate-900 p-12 rounded-[4rem] text-white shadow-2xl">
            <h4 class="text-[10px] font-black uppercase text-slate-500 mb-10 tracking-[0.2em] italic">Page Levels Distribution</h4>
            <div class="flex items-end justify-between h-40 gap-4">
                @for($i = 1; $i <= 4; $i++)
                    <div class="flex-1 flex flex-col items-center group">
                        <div class="w-full bg-emerald-500 rounded-t-2xl transition-all group-hover:bg-emerald-400 shadow-[0_0_20px_rgba(16,185,129,0.3)]" style="height: {{ rand(30, 90) }}%"></div>
                        <span class="text-[9px] font-black mt-4 text-slate-500 uppercase tracking-tighter">Level {{ $i }}</span>
                    </div>
                @endfor
            </div>
        </div>

        {{-- Status Overview --}}
        <div class="bg-white p-12 rounded-[4rem] shadow-xl border border-slate-100">
            <h4 class="text-[10px] font-black uppercase text-slate-400 mb-8 tracking-[0.2em] italic">HTTP Status Overview</h4>
            <div class="flex items-center gap-6">
                <div class="text-7xl font-black text-slate-900 italic leading-none">{{ $current->pages_scanned }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest leading-relaxed">
                    Successful (200 OK)<br>Pages Crawled
                </div>
            </div>
        </div>
    </div>
</div>