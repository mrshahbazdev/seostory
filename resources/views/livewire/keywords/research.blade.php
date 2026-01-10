<div class="h-full">
    <!-- Header Area -->
    <div class="mb-10 text-center max-w-2xl mx-auto">
        <h1 class="text-3xl font-extrabold text-navy-900 tracking-tight mb-3">Keyword Research Tool</h1>
        <p class="text-slate-500 text-lg">Find profitable keywords with search volume and difficulty data.</p>
    </div>

    <!-- Search Box -->
    <div class="max-w-3xl mx-auto mb-16">
        <form wire:submit.prevent="analyze" class="relative group">
            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                <svg class="h-6 w-6 text-slate-400 group-focus-within:text-seo-green-500 transition-colors" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" wire:model="term"
                class="block w-full pl-16 pr-32 py-5 bg-white border border-slate-200 rounded-2xl text-xl font-medium placeholder-slate-400 focus:ring-2 focus:ring-seo-green-500 focus:border-transparent transition-all shadow-xl shadow-slate-200/50"
                placeholder="Enter a keyword..." autofocus>
            <div class="absolute inset-y-2 right-2">
                <button type="submit"
                    class="h-full px-8 bg-navy-900 hover:bg-navy-800 text-white font-bold rounded-xl transition-all shadow-md">
                    <span wire:loading.remove>Search</span>
                    <span wire:loading>...</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Results Area -->
    @if($data)
        <div class="animate-fade-in-up">
            <!-- 1. Metrics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <!-- Volume -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest">Search Volume</h3>
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-4xl font-extrabold text-navy-900">{{ number_format($data['volume']) }}</p>
                    <p class="text-xs text-slate-400 mt-2 font-medium">Monthly Searches</p>
                </div>

                <!-- Difficulty (KD) -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest">Difficulty</h3>
                        <div class="p-2 bg-purple-50 text-purple-600 rounded-lg">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <p class="text-4xl font-extrabold text-navy-900">{{ $data['difficulty'] }}</p>
                        <span
                            class="px-2.5 py-0.5 rounded-md text-xs font-bold ring-1 ring-inset {{ $data['difficulty'] > 70 ? 'bg-red-50 text-red-700 ring-red-600/20' : ($data['difficulty'] > 30 ? 'bg-yellow-50 text-yellow-800 ring-yellow-600/20' : 'bg-green-50 text-green-700 ring-green-600/20') }}">
                            {{ $data['difficulty'] > 70 ? 'Very Hard' : ($data['difficulty'] > 30 ? 'Moderate' : 'Easy') }}
                        </span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 mt-4">
                        <div class="h-1.5 rounded-full {{ $data['difficulty'] > 70 ? 'bg-red-500' : ($data['difficulty'] > 30 ? 'bg-yellow-500' : 'bg-green-500') }}"
                            style="width: {{ $data['difficulty'] }}%"></div>
                    </div>
                </div>

                <!-- CPC -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest">Est. CPC</h3>
                        <div class="p-2 bg-green-50 text-green-600 rounded-lg">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-4xl font-extrabold text-navy-900">${{ $data['cpc'] }}</p>
                    <p class="text-xs text-slate-400 mt-2 font-medium">Cost Per Click</p>
                </div>
            </div>

            <!-- 2. Related Keywords -->
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="bg-slate-50/50 px-6 py-4 border-b border-slate-200">
                    <h3 class="font-bold text-navy-900">Related Keywords</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                <th class="px-6 py-4">Keyword</th>
                                <th class="px-6 py-4">Volume</th>
                                <th class="px-6 py-4">KD %</th>
                                <th class="px-6 py-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($data['results'] as $related)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-navy-900">{{ $related['term'] }}</td>
                                    <td class="px-6 py-4 text-slate-600">{{ number_format($related['volume']) }}</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $related['difficulty'] > 60 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $related['difficulty'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button wire:click="$set('term', '{{ $related['term'] }}')" wire:click.prevent="analyze"
                                            class="text-seo-green-600 hover:text-seo-green-700 text-sm font-bold">
                                            Analyze &rarr;
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>