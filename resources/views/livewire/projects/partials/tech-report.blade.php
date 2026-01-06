<div class="space-y-10 animate-fade-in">
    {{-- Header Card --}}
    <div class="bg-white p-12 rounded-[4rem] shadow-xl border border-slate-100 flex justify-between items-center">
        <div>
            <h2 class="text-4xl font-black text-slate-900 italic uppercase tracking-tighter">Technology & Meta</h2>
            <p class="text-xs font-bold text-slate-400 mt-3 uppercase tracking-widest italic">Temporal development of the optimization</p>
        </div>
        <div class="text-right">
            <span class="text-6xl font-black text-indigo-600 italic leading-none">{{ $current->score_tech }}%</span>
            <p class="text-[10px] font-black text-slate-400 uppercase mt-2 tracking-widest">Tech Degree</p>
        </div>
    </div>

    {{-- Meta Stats Table --}}
    <div class="bg-white rounded-[3.5rem] shadow-xl border border-slate-100 overflow-hidden">
        <div class="px-12 py-8 bg-slate-50/50 border-b border-slate-100">
            <h3 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em]">Meta Information Analysis</h3>
        </div>
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">
                    <th class="px-12 py-5 italic">Analysis Item</th>
                    <th class="px-12 py-5 text-center">Quantity</th>
                    <th class="px-12 py-5 text-right">Progress</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @php $techData = json_decode($current->tech_meta_data ?? '{}', true); @endphp
                
                <tr class="hover:bg-slate-50/50 transition group">
                    <td class="px-12 py-6 text-sm font-bold text-slate-600 italic group-hover:text-indigo-600">Duplicate page titles</td>
                    <td class="px-12 py-6 text-center font-black text-lg text-slate-900">{{ $techData['duplicate_titles'] ?? 0 }}</td>
                    <td class="px-12 py-6 text-right">
                        <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase {{ ($techData['duplicate_titles'] ?? 0) > 0 ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }}">
                            {{ ($techData['duplicate_titles'] ?? 0) > 0 ? 'Action Needed' : 'Done' }}
                        </span>
                    </td>
                </tr>

                <tr class="hover:bg-slate-50/50 transition group">
                    <td class="px-12 py-6 text-sm font-bold text-slate-600 italic group-hover:text-indigo-600">Problematic meta descriptions</td>
                    <td class="px-12 py-6 text-center font-black text-lg text-slate-900">{{ $techData['slow_pages'] ?? 0 }}</td>
                    <td class="px-12 py-6 text-right">
                        <span class="px-4 py-1.5 rounded-full text-[10px] font-black bg-indigo-50 text-indigo-600 uppercase">Analysis Complete</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>