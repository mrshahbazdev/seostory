<div wire:poll.5s class="max-w-[1600px] mx-auto">
    <nav class="flex mb-8 text-sm font-medium text-slate-400 space-x-2">
        <a href="/dashboard" class="hover:text-indigo-600 transition">Dashboard</a>
        <span>/</span>
        <span class="text-slate-900">Project: {{ $project->name }}</span>
    </nav>

    <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6">
        <div>
            <div class="flex items-center space-x-3 mb-2">
                <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">{{ $project->name }}</h1>
                <span class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-bold uppercase rounded-full tracking-widest">Live Monitoring</span>
            </div>
            <p class="text-slate-500 font-medium flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                {{ $project->url }}
            </p>
        </div>
        
        <div class="flex space-x-4">
            <button class="px-6 py-3 bg-white border border-slate-200 rounded-2xl font-bold text-slate-700 hover:bg-slate-50 transition shadow-sm">Settings</button>
            <button wire:click="$toggle('showAddModal')" class="px-6 py-3 bg-slate-900 text-white rounded-2xl font-bold hover:bg-black transition shadow-xl">Analyze New Competitor</button>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-10">
        <div class="xl:col-span-1">
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 sticky top-28">
                <h3 class="text-xl font-bold text-slate-900 mb-6">Add Competitor</h3>
                <form wire:submit.prevent="addCompetitor" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Brand Name</label>
                        <input type="text" wire:model="comp_name" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:ring-2 focus:ring-indigo-500 font-semibold text-sm" placeholder="e.g. Nike">
                        @error('comp_name') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Website URL</label>
                        <input type="text" wire:model="comp_url" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:ring-2 focus:ring-indigo-500 font-semibold text-sm" placeholder="https://nike.com">
                        @error('comp_url') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition-all transform hover:-translate-y-1 shadow-lg shadow-indigo-100">
                        Add & Start Crawling
                    </button>
                </form>
            </div>
        </div>

        <div class="xl:col-span-3 space-y-6">
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50/50 border-b border-slate-100">
                        <tr>
                            <th class="px-8 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Competitor</th>
                            <th class="px-8 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                            <th class="px-8 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center">Health</th>
                            <th class="px-8 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($competitors as $competitor)
                            <tr class="group hover:bg-slate-50/50 transition" wire:key="{{ $competitor->id }}">
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 bg-indigo-50 border border-indigo-100 rounded-xl flex items-center justify-center font-bold text-indigo-600 shadow-sm">
                                            {{ substr($competitor->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900">{{ $competitor->name }}</p>
                                            <p class="text-xs text-slate-400 font-medium truncate max-w-[200px]">{{ $competitor->website_url }}</p>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-8 py-6 text-center">
                                    @if($competitor->status == 'pending')
                                        <span class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-500 text-[10px] font-bold uppercase rounded-full tracking-widest border border-slate-200">
                                            In Queue
                                        </span>
                                    @elseif($competitor->status == 'fetching' || $competitor->status == 'analyzing')
                                        <span class="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase rounded-full tracking-widest border border-indigo-100 animate-pulse">
                                            <svg class="animate-spin -ml-1 mr-2 h-3 w-3 text-indigo-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            {{ $competitor->status }}...
                                        </span>
                                    @elseif($competitor->status == 'completed')
                                        <span class="inline-flex items-center px-3 py-1 bg-green-50 text-green-700 text-[10px] font-bold uppercase rounded-full tracking-widest border border-green-100">
                                            Ready
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 bg-rose-50 text-rose-700 text-[10px] font-bold uppercase rounded-full tracking-widest border border-rose-100">
                                            Failed
                                        </span>
                                    @endif
                                </td>

                                <td class="px-8 py-6">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                            <div class="bg-indigo-500 h-full w-[10%]"></div>
                                        </div>
                                        <span class="text-[10px] text-slate-400 mt-1 font-bold italic">Score: 0</span>
                                    </div>
                                </td>

                                <td class="px-8 py-6 text-right">
                                    <button wire:click="openAnalysis('{{ $competitor->id }}')" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition">
                                        View Report
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <p class="text-slate-400 font-medium italic">Your competitor list is empty.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>