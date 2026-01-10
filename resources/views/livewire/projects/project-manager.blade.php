<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
        <div>
            <h3 class="text-xl font-bold text-navy-900">Active Projects</h3>
            <p class="text-slate-500 text-sm">Monitor and manage your SEO campaigns.</p>
        </div>
        <button wire:click="$toggle('showCreateForm')"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-bold rounded-xl shadow-sm text-white bg-seo-green-500 hover:bg-seo-green-600 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-seo-green-500">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Project
        </button>
    </div>

    <!-- Create Form -->
    <div x-show="$wire.showCreateForm" x-transition
        class="mb-8 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <form wire:submit.prevent="createProject" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-500 uppercase">Project Name</label>
                <input type="text" wire:model="name"
                    class="block w-full border-slate-300 rounded-xl focus:ring-seo-green-500 focus:border-seo-green-500 sm:text-sm"
                    placeholder="My Website">
                @error('name') <span class="text-xs text-rose-500 font-medium">{{ $message }}</span> @enderror
            </div>
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-500 uppercase">Domain URL</label>
                <input type="text" wire:model="url"
                    class="block w-full border-slate-300 rounded-xl focus:ring-seo-green-500 focus:border-seo-green-500 sm:text-sm"
                    placeholder="https://example.com">
                @error('url') <span class="text-xs text-rose-500 font-medium">{{ $message }}</span> @enderror
            </div>
            <div class="flex items-end">
                <button type="submit"
                    class="w-full py-2.5 px-4 rounded-xl bg-navy-900 text-white text-sm font-bold hover:bg-navy-800 transition-all shadow-lg shadow-navy-900/20">
                    Create Project
                </button>
            </div>
        </form>
    </div>

    <!-- Project Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($projects as $project)
            <div
                class="group bg-white rounded-2xl p-6 border border-slate-200 hover:border-seo-green-500 hover:shadow-lg hover:shadow-green-500/5 transition-all duration-300 relative">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-lg bg-navy-50 flex items-center justify-center text-navy-700 font-bold border border-navy-100">
                            {{ substr($project->name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-navy-900 group-hover:text-seo-green-600 transition-colors">
                                {{ $project->name }}</h4>
                            <a href="{{ $project->url }}" target="_blank"
                                class="text-xs text-slate-400 hover:text-navy-700 truncate block max-w-[150px]">{{ parse_url($project->url, PHP_URL_HOST) }}</a>
                        </div>
                    </div>
                    <!-- Menu/Actions could go here -->
                    <span class="inline-flex items-center px-2 py-1 rounded bg-slate-100 text-slate-500 text-xs font-bold">
                        Draft
                    </span>
                </div>

                <!-- Health Bars Template (Static for now) -->
                <div class="grid grid-cols-3 gap-2 py-4 border-t border-b border-slate-50 my-4">
                    <div class="text-center">
                        <span class="block text-lg font-bold text-slate-300">-</span>
                        <span class="text-[10px] text-slate-400 uppercase font-bold">Health</span>
                    </div>
                    <div class="text-center border-l border-slate-100">
                        <span class="block text-lg font-bold text-slate-300">-</span>
                        <span class="text-[10px] text-slate-400 uppercase font-bold">Visibility</span>
                    </div>
                    <div class="text-center border-l border-slate-100">
                        <span class="block text-lg font-bold text-slate-300">-</span>
                        <span class="text-[10px] text-slate-400 uppercase font-bold">Backlinks</span>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-400">Never crawled</span>
                    <a href="{{ route('projects.show', $project->id) }}" wire:navigate
                        class="inline-flex items-center text-sm font-bold text-navy-900 hover:text-seo-green-600 transition-colors">
                        Open Dashboard <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        @empty
            <div
                class="col-span-full py-16 bg-white rounded-2xl border border-dashed border-slate-300 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <h3 class="text-navy-900 font-bold text-lg">No Projects Yet</h3>
                <p class="text-slate-500 max-w-sm mt-2 mb-6">Create your first project to start tracking rankings and
                    crawling for issues.</p>
                <button wire:click="$toggle('showCreateForm')"
                    class="px-5 py-2.5 bg-seo-green-500 text-white font-bold rounded-xl hover:bg-seo-green-600 transition-all shadow-lg shadow-seo-green-500/20">
                    Create First Project
                </button>
            </div>
        @endforelse
    </div>
</div>