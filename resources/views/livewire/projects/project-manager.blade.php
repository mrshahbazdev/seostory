<div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm shadow-slate-200/50">
            <p class="text-sm font-medium text-slate-500 mb-1">Total Projects</p>
            <h4 class="text-3xl font-extrabold text-slate-900">{{ $projects->count() }}</h4>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm shadow-slate-200/50">
            <p class="text-sm font-medium text-slate-500 mb-1">Active Monitors</p>
            <h4 class="text-3xl font-extrabold text-indigo-600">0</h4>
        </div>
        <div class="bg-indigo-600 p-6 rounded-3xl shadow-xl shadow-indigo-100 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-sm font-medium text-indigo-100 mb-1">AI Credits</p>
                <h4 class="text-3xl font-extrabold text-white">5,000</h4>
            </div>
            <svg class="absolute -right-4 -bottom-4 w-24 h-24 text-indigo-500 opacity-20 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM5.884 6.68a1 1 0 10-1.404-1.427l-.707.696a1 1 0 101.404 1.427l.707-.696zM14.823 5.253a1 1 0 00-1.404 1.427l.707.696a1 1 0 101.404-1.427l-.707-.696zM14 10a4 4 0 11-8 0 4 4 0 018 0zM4.644 14.116a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM15.356 14.116a1 1 0 001.414-1.414l.707-.707a1 1 0 00-1.414 1.414l-.707.707zM8 17a1 1 0 100-2H7a1 1 0 100 2h1zM11 17a1 1 0 100-2h1a1 1 0 100 2h-1z"></path></svg>
        </div>
        <div class="bg-white p-6 rounded-3xl border-2 border-dashed border-slate-200 flex items-center justify-center">
            <button class="text-sm font-bold text-slate-400 hover:text-indigo-600 transition">+ New Metric</button>
        </div>
    </div>

    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight">Project Matrix</h3>
            <p class="text-slate-500 text-sm">Monitor and manage your digital assets across the workspace.</p>
        </div>
        <button wire:click="$toggle('showCreateForm')" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-bold rounded-2xl shadow-lg shadow-indigo-100 text-white bg-indigo-600 hover:bg-indigo-700 transition-all focus:outline-none">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
            Launch New Project
        </button>
    </div>

    <div class="mb-10 p-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-[2rem] shadow-xl shadow-indigo-100">
        <div class="bg-white/95 backdrop-blur-sm rounded-[1.9rem] p-8">
            <form wire:submit.prevent="createProject" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="space-y-2">
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Identity</label>
                    <input type="text" wire:model="name" class="block w-full px-4 py-4 rounded-2xl bg-slate-50 border-transparent focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all text-sm font-semibold" placeholder="Project Name">
                    @error('name') <span class="text-xs text-rose-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>
                <div class="space-y-2">
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Entry Endpoint</label>
                    <input type="text" wire:model="url" class="block w-full px-4 py-4 rounded-2xl bg-slate-50 border-transparent focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all text-sm font-semibold" placeholder="https://domain.com">
                    @error('url') <span class="text-xs text-rose-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full h-14 rounded-2xl bg-slate-900 text-white text-sm font-bold hover:bg-black transition-all transform hover:-translate-y-1">
                        Register Asset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
        @forelse($projects as $project)
            <div class="group bg-white rounded-[2.5rem] p-8 border border-slate-100 hover:border-indigo-200 shadow-sm hover:shadow-2xl hover:shadow-indigo-100 transition-all duration-500 relative overflow-hidden">
                <div class="flex justify-between items-start mb-6">
                    <div class="p-4 bg-indigo-50 rounded-2xl text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9b9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                    </div>
                    <button class="text-slate-300 hover:text-rose-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                    </button>
                </div>
                
                <h4 class="text-xl font-extrabold text-slate-900 mb-2 group-hover:text-indigo-600 transition-colors">{{ $project->name }}</h4>
                <p class="text-sm font-medium text-slate-400 truncate mb-6">{{ $project->url }}</p>
                
                <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                    <div class="flex -space-x-2">
                        <div class="w-8 h-8 rounded-full border-2 border-white bg-slate-200 flex items-center justify-center text-[10px] font-bold">AI</div>
                        <div class="w-8 h-8 rounded-full border-2 border-white bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-400">?</div>
                    </div>
                    <a href="{{ route('projects.show', $project->id) }}" wire:navigate class="text-sm font-bold text-gray-700 hover:text-indigo-600 transition">
                        View Details →
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 bg-white rounded-[3rem] border border-dashed border-slate-200 flex flex-col items-center">
                <div class="p-6 bg-slate-50 rounded-full mb-4">
                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
                <p class="text-slate-400 font-medium">No system assets found in this workspace.</p>
            </div>
        @endforelse
    </div>
</div>