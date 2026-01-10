<div class="min-h-screen bg-slate-50" wire:init="runAudit">
    <!-- Header -->
    <header class="bg-navy-900 border-b border-navy-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-seo-green-500 rounded flex items-center justify-center">
                     <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <a href="/" class="text-xl font-bold text-white">SeoStory</a>
            </div>
            <a href="/" class="text-sm font-semibold text-slate-300 hover:text-white">Start New Audit</a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @if($loading)
            <div class="flex flex-col items-center justify-center py-20">
                <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-seo-green-500 mb-4"></div>
                <h2 class="text-xl font-semibold text-navy-800">Analyzing {{ $url }}...</h2>
                <p class="text-slate-500 mt-2">Checking meta tags, structure, and loading speed.</p>
            </div>
        @elseif($error)
             <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                <h2 class="text-lg font-bold text-red-700">Audit Failed</h2>
                <p class="text-red-600 mt-2">{{ $error }}</p>
                <a href="/" class="inline-block mt-4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Try Again</a>
            </div>
        @else
            <!-- Results Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-navy-900 truncate">SEO Audit: {{ $url }}</h1>
                <p class="text-slate-500 text-sm mt-1">Generated {{ $audit->updated_at->diffForHumans() }}</p>
            </div>

            <!-- Scores Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
                <!-- Overall Score -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex flex-col items-center">
                    <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4">Overall Score</h3>
                    <div class="relative w-32 h-32 flex items-center justify-center rounded-full border-8 {{ $audit->overall_health_score > 80 ? 'border-seo-green-500' : ($audit->overall_health_score > 50 ? 'border-yellow-400' : 'border-red-500') }}">
                        <span class="text-4xl font-bold text-navy-900">{{ $audit->overall_health_score }}</span>
                    </div>
                </div>

                <!-- Sub Scores -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 col-span-3 grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="text-center">
                        <span class="block text-2xl font-bold text-navy-900">{{ $audit->score_meta }}%</span>
                        <span class="text-sm text-slate-500 font-medium">Meta Information</span>
                        <div class="w-full bg-slate-200 rounded-full h-2 mt-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $audit->score_meta }}%"></div>
                        </div>
                    </div>
                    <div class="text-center">
                        <span class="block text-2xl font-bold text-navy-900">{{ $audit->score_structure }}%</span>
                        <span class="text-sm text-slate-500 font-medium">Page Structure</span>
                        <div class="w-full bg-slate-200 rounded-full h-2 mt-2">
                            <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $audit->score_structure }}%"></div>
                        </div>
                    </div>
                    <div class="text-center">
                        <span class="block text-2xl font-bold text-navy-900">{{ $audit->score_tech }}%</span>
                        <span class="text-sm text-slate-500 font-medium">Technical & Speed</span>
                        <div class="w-full bg-slate-200 rounded-full h-2 mt-2">
                            <div class="bg-orange-500 h-2 rounded-full" style="width: {{ $audit->score_tech }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Breakdown -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Meta Analysis -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100">
                        <h3 class="font-bold text-navy-900">Meta Analysis</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase">Page Title</p>
                                <p class="text-navy-900 font-medium mt-1">{{ is_array($audit->tech_meta_data) ? ($audit->tech_meta_data['data']['title'] ?? 'Missing') : 'N/A' }}</p>
                            </div>
                            @if(empty($audit->tech_meta_data['issues']))
                                <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded">OK</span>
                            @endif
                        </div>
                         <!-- Issues -->
                        @if(!empty($audit->tech_meta_data['issues']))
                            <div class="bg-red-50 p-3 rounded-lg space-y-2">
                                @foreach($audit->tech_meta_data['issues'] as $issue)
                                    <div class="flex items-start gap-2 text-sm text-red-700">
                                        <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        {{ $issue['message'] }}
                                    </div>
                                @endforeach
                            </div>
                        @else
                           <p class="text-green-600 text-sm flex items-center gap-2"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> No meta issues found.</p>
                        @endif
                    </div>
                </div>

                <!-- Structure -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100">
                        <h3 class="font-bold text-navy-900">Structure & Content</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase">H1 Count</p>
                                <p class="text-xl font-bold text-navy-900">{{ $audit->structure_data['data']['h1_count'] ?? 0 }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase">Images</p>
                                <p class="text-xl font-bold text-navy-900">{{ $audit->structure_data['data']['images_count'] ?? 0 }}</p>
                            </div>
                        </div>
                        
                         <!-- Issues -->
                         @if(!empty($audit->structure_data['issues']))
                            <div class="bg-yellow-50 p-3 rounded-lg space-y-2">
                                @foreach($audit->structure_data['issues'] as $issue)
                                    <div class="flex items-start gap-2 text-sm text-yellow-800">
                                        <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                        {{ $issue['message'] }}
                                    </div>
                                @endforeach
                            </div>
                         @else
                             <p class="text-green-600 text-sm flex items-center gap-2"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Structure looks good.</p>
                         @endif
                    </div>
                </div>

                <!-- Tech -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100">
                        <h3 class="font-bold text-navy-900">Technical</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                             <p class="text-xs font-bold text-slate-400 uppercase">Load Time</p>
                             <p class="text-2xl font-bold {{ ($audit->summary_data['data']['load_time_ms'] ?? 1000) < 1000 ? 'text-green-600' : 'text-orange-500' }}">
                                {{ $audit->summary_data['data']['load_time_ms'] ?? 0 }}ms
                             </p>
                        </div>
                         <!-- Issues -->
                         @if(!empty($audit->summary_data['issues']))
                            <div class="bg-orange-50 p-3 rounded-lg space-y-2">
                                @foreach($audit->summary_data['issues'] as $issue)
                                    <div class="flex items-start gap-2 text-sm text-orange-800">
                                        <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                        {{ $issue['message'] }}
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-green-600 text-sm flex items-center gap-2"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Performance is excellent.</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </main>
</div>
