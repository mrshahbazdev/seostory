<div class="space-y-10 animate-fade-in">
    <div class="bg-white p-12 rounded-[4rem] shadow-xl border border-slate-100 flex justify-between items-center border-t-8 border-amber-500">
        <div>
            <h2 class="text-4xl font-black text-slate-900 italic uppercase tracking-tighter">Content Quality</h2>
            <p class="text-xs font-bold text-slate-400 mt-3 uppercase tracking-widest italic">Keyword Analysis & Copy Optimization</p>
        </div>
        <div class="text-right">
            <span class="text-6xl font-black text-amber-500 italic leading-none">{{ $current->score_content }}%</span>
        </div>
    </div>

    <div class="bg-white rounded-[3.5rem] shadow-xl border border-slate-100 overflow-hidden">
        <table class="w-full text-left">
            <tbody class="divide-y divide-slate-50">
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-12 py-8 text-sm font-bold text-slate-600 italic">Thin content pages (< 300 words)</td>
                    <td class="px-12 py-8 font-black text-2xl text-slate-900">0</td>
                    <td class="px-12 py-8 text-right">
                        <span class="px-4 py-2 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase rounded-xl tracking-widest">Optimized</span>
                    </td>
                </tr>
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-12 py-8 text-sm font-bold text-slate-600 italic">Keyword consistency in titles</td>
                    <td class="px-12 py-8 font-black text-2xl text-slate-900">88%</td>
                    <td class="px-12 py-8 text-right">
                        <span class="px-4 py-2 bg-amber-50 text-amber-600 text-[10px] font-black uppercase rounded-xl tracking-widest">Monitor</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>