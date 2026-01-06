<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    {{-- Main Dashboard Content --}}
    <div wire:poll.15s class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 {{ !$project->is_verified ? 'blur-sm pointer-events-none opacity-50' : '' }}">
        
        {{-- Navigation --}}
        <nav class="flex items-center space-x-2 mb-10">
            <a href="/dashboard" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors">
                Dashboard
            </a>
            <svg class="h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
            <span class="text-sm font-semibold text-gray-900">Project: {{ $project->name }}</span>
        </nav>

        {{-- Header Section --}}
        <div class="bg-white rounded-2xl shadow-xl mb-8 p-8 border border-gray-200">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-4 mb-4">
                        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">{{ $project->name }}</h1>
                        <div class="flex items-center px-3 py-1 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full shadow-sm">
                            <span class="w-2 h-2 bg-white rounded-full animate-pulse mr-2"></span>
                            <span class="text-xs font-semibold text-white uppercase tracking-wider">Active Audit</span>
                        </div>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 10-5.656-5.656l-1.1 1.1" />
                        </svg>
                        <span class="font-medium">{{ $project->url }}</span>
                    </div>
                </div>
                
                <div class="flex gap-4">
                    <button wire:click="startSelfAudit" wire:loading.attr="disabled"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:shadow-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-md">
                        <svg wire:loading.remove class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <svg wire:loading class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove>Run Full Site Audit</span>
                        <span wire:loading>Processing...</span>
                    </button>
                    
                    <button class="inline-flex items-center px-4 py-3 border border-gray-300 bg-white text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Export Report
                    </button>
                </div>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Left Column - Audit History --}}
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Site Health Reports</h2>
                                <p class="text-sm text-gray-500 mt-1">Historical technical SEO audits</p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="text-right">
                                    <p class="text-xs font-medium text-gray-500">Current Score</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $audits->first()?->overall_health_score ?? '0' }}%</p>
                                </div>
                                <div class="w-12 h-12 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            @forelse($audits as $audit)
                                <div class="group bg-gray-50 hover:bg-white border border-gray-200 hover:border-indigo-200 hover:shadow-md rounded-xl p-5 transition-all duration-200 cursor-pointer">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-white border border-gray-300 rounded-lg flex items-center justify-center font-semibold text-gray-700">
                                                #{{ $audits->count() - $loop->index }}
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ $audit->created_at->format('M d, Y • h:i A') }}</h4>
                                                <div class="flex items-center space-x-3 mt-1">
                                                    <span class="text-xs font-medium text-gray-500">{{ $audit->pages_scanned }} pages scanned</span>
                                                    @if($audit->status === 'processing')
                                                    <button wire:click="openCrawlerStatus" class="inline-flex items-center px-2 py-1 bg-yellow-50 text-yellow-700 text-xs font-medium rounded-full hover:bg-yellow-100 transition-colors">
                                                        <span class="w-2 h-2 bg-yellow-500 rounded-full animate-ping mr-1"></span>
                                                        Scanning
                                                    </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center space-x-6">
                                            <div class="text-right">
                                                <div class="text-lg font-semibold {{ $audit->overall_health_score > 80 ? 'text-green-600' : ($audit->overall_health_score > 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                                    {{ $audit->overall_health_score }}%
                                                </div>
                                                <div class="text-xs text-gray-500">Health Score</div>
                                            </div>
                                            <button wire:click="viewAudit({{ $audit->id }})" 
                                                class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-black transition-colors">
                                                View Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12 border-2 border-dashed border-gray-300 rounded-xl">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="mt-4 text-sm text-gray-500">No audits performed yet. Run your first site audit to get started.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column - Competitor Analysis --}}
            <div class="space-y-8">
                <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex items-center mb-6">
                        <div class="w-2 h-8 bg-indigo-500 rounded-full mr-3"></div>
                        <h3 class="text-lg font-semibold">Competitor Analysis</h3>
                    </div>

                    <form wire:submit.prevent="addCompetitor" class="space-y-4 mb-8">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Target Brand</label>
                            <input type="text" wire:model="comp_name" 
                                class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="e.g., Nike">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Main URL</label>
                            <input type="text" wire:model="comp_url" 
                                class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="https://example.com">
                        </div>
                        <button type="submit" 
                            class="w-full py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold rounded-lg hover:from-indigo-600 hover:to-purple-700 transition-all duration-200 shadow-md">
                            Add Competitor
                        </button>
                    </form>

                    <div class="space-y-4">
                        @foreach($competitors as $competitor)
                            <div class="bg-gray-800/50 border border-gray-700 rounded-xl p-4 hover:border-indigo-500 transition-colors">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-medium">{{ $competitor->name }}</h4>
                                    <span class="px-2 py-1 bg-gray-700 text-xs font-medium rounded-full">{{ $competitor->status }}</span>
                                </div>
                                <button wire:click="startCompetitorAudit('{{ $competitor->id }}')" 
                                    class="w-full py-2 bg-gray-700 hover:bg-gray-600 text-sm font-medium rounded-lg transition-colors">
                                    Analyze Site
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Quick Stats --}}
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 rounded-xl p-4">
                            <div class="text-2xl font-bold text-blue-600">{{ $audits->count() }}</div>
                            <div class="text-sm text-blue-700 font-medium">Total Audits</div>
                        </div>
                        <div class="bg-green-50 rounded-xl p-4">
                            {{-- FIXED: Check if pages relationship exists before counting --}}
                            @php
                                $pageCount = 0;
                                foreach($audits as $audit) {
                                    $pageCount += $audit->projectPages ? $audit->projectPages->count() : 0;
                                }
                            @endphp
                            <div class="text-2xl font-bold text-green-600">{{ $pageCount }}</div>
                            <div class="text-sm text-green-700 font-medium">Pages Scanned</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Verification Modal --}}
    @if(!$project->is_verified)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden animate-fade-in">
                <div class="p-8 text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Verify Ownership</h2>
                    <p class="text-gray-600 mb-6">Add this meta tag to your site's <code class="text-indigo-600 font-mono">&lt;head&gt;</code> section to continue.</p>

                    <div class="bg-gray-50 border border-gray-300 rounded-lg p-4 mb-6">
                        <code class="text-sm text-gray-800 font-mono break-all select-all">
                            &lt;meta name="seostory-verify" content="{{ $project->verification_token }}"&gt;
                        </code>
                    </div>

                    @if (session()->has('error'))
                        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 text-sm rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    <button wire:click="verifySite" wire:loading.attr="disabled"
                        class="w-full py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-md mb-4">
                        <span wire:loading.remove>Verify Ownership</span>
                        <span wire:loading>Checking...</span>
                    </button>
                    
                    <a href="/dashboard" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                        Return to Dashboard
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- Audit Details Modal --}}
    @if($showAuditModal && $selectedAudit)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col">
                <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Audit Details</h2>
                        <p class="text-sm text-gray-500">{{ $selectedAudit->created_at->format('M d, Y') }} • {{ $selectedAudit->pages_scanned }} pages</p>
                    </div>
                    <button wire:click="$set('showAuditModal', false)" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="flex-1 overflow-y-auto p-6">
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Page URL</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Load Time</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Health</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issues</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                {{-- FIXED: Check if projectPages exists --}}
                                @if($selectedAudit->projectPages)
                                    @foreach($selectedAudit->projectPages as $page)
                                        @php 
                                            $data = json_decode($page->full_audit_data ?? '{}', true);
                                        @endphp
                                        <tr class="hover:bg-gray-50 cursor-pointer" wire:click="inspectPage({{ $page->id }})">
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900 truncate max-w-xs">{{ $page->url }}</div>
                                                <div class="text-xs text-gray-500">{{ $page->title ?? 'No title' }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $page->status == 'audited' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ $page->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <span class="{{ ($page->load_time ?? 0) > 2 ? 'text-red-600 font-medium' : 'text-gray-900' }}">
                                                    {{ $page->load_time ?? '0.00' }}s
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                                                        <div class="bg-gradient-to-r {{ ($page->health_score ?? 0) > 80 ? 'from-green-500 to-green-600' : (($page->health_score ?? 0) > 60 ? 'from-yellow-500 to-yellow-600' : 'from-red-500 to-red-600') }} h-2 rounded-full" style="width: {{ $page->health_score ?? 0 }}%"></div>
                                                    </div>
                                                    <span class="text-sm font-medium">{{ $page->health_score ?? '0' }}%</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                @if(isset($data['issues']) && count($data['issues']) > 0)
                                                    <div class="text-xs text-red-600 font-medium">
                                                        {{ count($data['issues']) }} issues
                                                    </div>
                                                @else
                                                    <span class="text-xs text-green-600 font-medium">✓ No issues</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            No pages found for this audit.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                    <button wire:click="$set('showAuditModal', false)" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-black transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Page Detail Modal --}}
    @if($showPageDetailModal && $activePageData)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col">
                <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-t-2xl">
                    <div>
                        <h2 class="text-xl font-semibold">Page Analysis</h2>
                        <p class="text-sm text-indigo-100 truncate max-w-lg">{{ $activePageData['summary']['url'] ?? 'N/A' }}</p>
                    </div>
                    <button wire:click="$set('showPageDetailModal', false)" class="text-white hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="flex-1 overflow-y-auto p-6">
                    {{-- Stats Grid --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4">
                            <div class="text-2xl font-bold text-blue-700">{{ $activePageData['summary']['health'] ?? '0' }}%</div>
                            <div class="text-sm font-medium text-blue-800">Health Score</div>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-4">
                            <div class="text-2xl font-bold text-green-700">{{ $activePageData['content']['word_count'] ?? '0' }}</div>
                            <div class="text-sm font-medium text-green-800">Word Count</div>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-4">
                            <div class="text-2xl font-bold text-purple-700">{{ $activePageData['content']['internal_links'] ?? '0' }}</div>
                            <div class="text-sm font-medium text-purple-800">Internal Links</div>
                        </div>
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-xl p-4">
                            <div class="text-2xl font-bold text-orange-700">{{ $activePageData['content']['images_total'] ?? '0' }}</div>
                            <div class="text-sm font-medium text-orange-800">Images</div>
                        </div>
                    </div>

                    {{-- Two Column Layout --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- SEO Section --}}
                        <div class="space-y-6">
                            <div class="border border-gray-200 rounded-xl p-5">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">SEO Meta Data</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg text-sm">{{ $activePageData['seo']['title'] ?? 'Not found' }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg text-sm">{{ $activePageData['seo']['description'] ?? 'Not found' }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="border border-gray-200 rounded-xl p-5">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Heading Structure</h3>
                                <div class="space-y-2">
                                    @if(!empty($activePageData['content']['h1']))
                                        @foreach($activePageData['content']['h1'] as $h1)
                                            <div class="p-2 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-800">H1: {{ $h1 }}</div>
                                        @endforeach
                                    @endif
                                    @if(!empty($activePageData['content']['h2']))
                                        @foreach($activePageData['content']['h2'] as $h2)
                                            <div class="p-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 ml-2">H2: {{ $h2 }}</div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Technical Details Section --}}
                        <div class="space-y-6">
                            <div class="border border-gray-200 rounded-xl p-5 bg-gradient-to-br from-gray-50 to-white">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Technical Details</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Server</span>
                                        <span class="text-sm font-medium">{{ $activePageData['technical']['server'] ?? 'Unknown' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Compression</span>
                                        <span class="text-sm font-medium text-green-600">
                                            {{ ($activePageData['technical']['is_compressed'] ?? false) ? 'Enabled' : 'Disabled' }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Status Code</span>
                                        <span class="text-sm font-medium text-green-600">200 OK</span>
                                    </div>
                                </div>
                            </div>

                            <div class="border border-gray-200 rounded-xl p-5">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Schema Markup</h3>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($activePageData['schemas'] ?? [] as $schema)
                                        <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-xs font-medium rounded-full">
                                            {{ is_array($schema) ? ($schema['@type'] ?? 'Unknown') : $schema }}
                                        </span>
                                    @empty
                                        <span class="text-sm text-gray-500 italic">No schema markup detected</span>
                                    @endforelse
                                </div>
                            </div>

                            <div class="border border-gray-200 rounded-xl p-5">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Issues Found</h3>
                                <div class="space-y-2">
                                    @if(isset($activePageData['issues']) && count($activePageData['issues']) > 0)
                                        @foreach($activePageData['issues'] as $issue)
                                            <div class="flex items-start p-3 bg-red-50 border border-red-200 rounded-lg">
                                                <span class="text-red-500 mr-2">⚠</span>
                                                <span class="text-sm text-red-700">{{ $issue['msg'] ?? 'Unknown issue' }}</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center py-4 text-green-600">
                                            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-sm font-medium">No critical issues found</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                    <button wire:click="$set('showPageDetailModal', false)" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-black transition-colors">
                        Close Details
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Analysis Modal --}}
    @if($showAnalysisModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] flex flex-col">
                <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">AI Analysis</h2>
                    <button wire:click="$set('showAnalysisModal', false)" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="flex-1 overflow-y-auto p-6 prose prose-indigo max-w-none">
                    {!! Str::markdown($activeAnalysis) !!}
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.prose {
    color: #374151;
}

.prose h3 {
    color: #111827;
    font-weight: 600;
    margin-top: 1.5em;
}

.prose ul {
    list-style-type: disc;
    padding-left: 1.5em;
}

.prose li {
    margin-bottom: 0.5em;
}
</style>