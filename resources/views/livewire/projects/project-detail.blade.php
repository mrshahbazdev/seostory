<div wire:poll.5s class="max-w-[1600px] mx-auto pb-20">
    <nav class="flex mb-8 text-sm font-medium text-slate-400 space-x-2">
        <a href="/dashboard" class="hover:text-indigo-600 transition">Dashboard</a>
        <span>/</span>
        <span class="text-slate-900">Project: {{ $project->name }}</span>
    </nav>

    <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6">
        <div>
            <div class="flex items-center space-x-3 mb-2">
                <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">{{ $project->name }}</h1>
                <span class="px-3 py-1 bg-indigo-600 text-white text-[10px] font-bold uppercase rounded-full tracking-widest shadow-lg shadow-indigo-200">
                    AI Enabled
                </span>
            </div>
            <p class="text-slate-500 font-medium flex items-center">
                <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                {{ $project->url }}
            </p>
        </div>
        
        <div class="flex space-x-4">
            <button class="px-6 py-3 bg-white border border-slate-200 rounded-2xl font-bold text-slate-700 hover:bg-slate-50 transition shadow-sm">Settings</button>
            <button wire:click="$set('showAddModal', true)" class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition shadow-xl shadow-indigo-200">
                + Add Competitor
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-10">
        <div class="xl:col-span-1">
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 sticky top-28">
                <h3 class="text-xl font-bold text-slate-900 mb-6">Market Intel</h3>
                <form wire:submit.prevent="addCompetitor" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Brand Name</label>
                        <input type="text" wire:model="comp_name" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:ring-2 focus:ring-indigo-500 font-semibold text-sm transition-all" placeholder="e.g. Nike">
                        @error('comp_name') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Website URL</label>
                        <input type="text" wire:model="comp_url" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:ring-2 focus:ring-indigo-500 font-semibold text-sm transition-all" placeholder="https://nike.com">
                        @error('comp_url') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full py-5 bg-slate-900 text-white rounded-2xl font-bold hover:bg-black transition-all transform hover:-translate-y-1 shadow-lg shadow-slate-200">
                        🚀 Start Analysis
                    </button>
                </form>
            </div>
        </div>

        <div class="xl:col-span-3 space-y-8">
            <div class="grid grid-cols-1 gap-6">
                @forelse($competitors as $competitor)
                    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-all p-6 relative overflow-hidden group" wire:key="{{ $competitor->id }}">
                        
                        <div class="flex items-start justify-between mb-6">
                            <div class="flex items-center space-x-4">
                                <div class="w-14 h-14 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center font-black text-2xl text-slate-900">
                                    {{ substr($competitor->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-slate-900">{{ $competitor->name }}</h4>
                                    <a href="{{ $competitor->website_url }}" target="_blank" class="text-xs text-indigo-500 font-medium hover:underline flex items-center">
                                        {{ $competitor->website_url }}
                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                </div>
                            </div>

                            <div class="text-right">
                                @if($competitor->status == 'completed')
                                    <span class="inline-flex items-center px-4 py-1.5 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase rounded-full border border-emerald-100">
                                        Analysis Ready
                                    </span>
                                @elseif($competitor->status == 'failed')
                                    <span class="inline-flex items-center px-4 py-1.5 bg-rose-50 text-rose-600 text-[10px] font-black uppercase rounded-full border border-rose-100">
                                        System Blocked
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-4 py-1.5 bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase rounded-full border border-indigo-100 animate-pulse">
                                        {{ $competitor->status }}...
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 py-4 border-y border-slate-50 mb-6">
                            <div class="text-center md:text-left">
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Words</p>
                                <p class="text-lg font-bold text-slate-800">{{ $competitor->metadata['seo_report']['stats']['words'] ?? '0' }}</p>
                            </div>
                            <div class="text-center md:text-left">
                                <p class="text-[10px] font-bold text-slate-400 uppercase">H1 Tags</p>
                                <p class="text-lg font-bold text-slate-800">{{ $competitor->metadata['seo_report']['tags']['h1_count'] ?? '0' }}</p>
                            </div>
                            <div class="text-center md:text-left">
                                <p class="text-[10px] font-bold text-slate-400 uppercase">H2 Tags</p>
                                <p class="text-lg font-bold text-slate-800">{{ $competitor->metadata['seo_report']['tags']['h2_count'] ?? '0' }}</p>
                            </div>
                            <div class="text-center md:text-left">
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Images (No Alt)</p>
                                <p class="text-lg font-bold text-rose-500">{{ $competitor->metadata['seo_report']['images']['missing_alt'] ?? '0' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-6">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase mb-1">SEO Health</span>
                                    <div class="w-32 bg-slate-100 h-2 rounded-full overflow-hidden">
                                        <div class="bg-indigo-600 h-full transition-all" style="width: {{ $competitor->status == 'completed' ? rand(70,95) : '10' }}%"></div>
                                    </div>
                                </div>
                                @if(isset($competitor->metadata['seo_report']['title']))
                                    <div class="hidden md:block">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase mb-1">Page Title</span>
                                        <p class="text-xs text-slate-600 italic">"{{ Str::limit($competitor->metadata['seo_report']['title'], 50) }}"</p>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center space-x-3">
                                @if($competitor->status == 'fetching_completed')
                                    <button wire:click="runAI('{{ $competitor->id }}')" class="px-5 py-2.5 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-black shadow-lg shadow-slate-200 transition">
                                        Run AI Analysis
                                    </button>
                                @endif

                                @if($competitor->status == 'completed')
                                    <button wire:click="openAnalysis('{{ $competitor->id }}')" class="px-5 py-2.5 bg-indigo-600 text-white text-xs font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition">
                                        View Insights
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white border-2 border-dashed border-slate-200 rounded-[3rem] py-32 text-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">No Competitors Scanned</h3>
                        <p class="text-slate-400">Add a URL on the left to start the deep crawling engine.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @if($showAnalysisModal)
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white w-full max-w-4xl rounded-[3rem] shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
                <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight">AI Strategy Report</h2>
                        <p class="text-slate-500 text-sm font-medium">Deep analysis by GPT-4o-mini</p>
                    </div>
                    <button wire:click="$set('showAnalysisModal', false)" class="p-3 bg-white rounded-2xl hover:bg-slate-100 transition shadow-sm border border-slate-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-10 overflow-y-auto custom-scrollbar">
                    <div class="prose prose-slate prose-indigo max-w-none">
                        {!! Str::markdown($activeAnalysis) !!}
                    </div>
                </div>
                <div class="p-8 border-t border-slate-50 bg-slate-50/50 flex justify-end space-x-4">
                    <button class="px-6 py-3 bg-white border border-slate-200 rounded-2xl font-bold text-slate-700 hover:bg-slate-50 transition">Export PDF</button>
                    <button wire:click="$set('showAnalysisModal', false)" class="px-6 py-3 bg-slate-900 text-white rounded-2xl font-bold hover:bg-black transition">Close Report</button>
                </div>
            </div>
        </div>
    @endif
</div>