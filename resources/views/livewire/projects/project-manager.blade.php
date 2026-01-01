<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="text-sm font-medium text-gray-400">Total Projects</div>
            <div class="text-3xl font-bold text-gray-800">{{ $projects->count() }}</div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="text-sm font-medium text-gray-400">Active Analysis</div>
            <div class="text-3xl font-bold text-indigo-600">0</div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center flex flex-col justify-center items-center border-dashed border-2 border-indigo-100">
            <button class="text-indigo-600 font-semibold hover:text-indigo-700">+ Upgrade Plan</button>
        </div>
    </div>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Projects</h1>
            <p class="text-sm text-gray-500">Manage and monitor your competitor analysis projects.</p>
        </div>
        <button wire:click="$set('showCreateForm', true)" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-semibold transition shadow-md shadow-indigo-200">
            + New Project
        </button>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 mb-8">
        <form wire:submit.prevent="createProject" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-500 uppercase">Project Name</label>
                <input type="text" wire:model="name" class="w-full bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-indigo-500 p-3" placeholder="Enter name...">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-500 uppercase">Target URL</label>
                <input type="text" wire:model="url" class="w-full bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-indigo-500 p-3" placeholder="https://example.com">
                @error('url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-slate-800 text-white p-3 rounded-xl font-bold hover:bg-slate-900 transition">Create Project</button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($projects as $project)
            <div class="bg-white border border-gray-100 rounded-3xl p-6 hover:border-indigo-300 transition-all duration-300 shadow-sm hover:shadow-xl group">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div class="flex space-x-2">
                        <button class="text-gray-400 hover:text-red-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                    </div>
                </div>
                <h3 class="font-bold text-lg text-gray-900 mb-1">{{ $project->name }}</h3>
                <p class="text-xs text-gray-400 truncate mb-4">{{ $project->url }}</p>
                <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                    <span class="text-[10px] font-bold text-indigo-500 bg-indigo-50 px-2 py-1 rounded-full uppercase">Monitoring</span>
                    <a href="#" class="text-sm font-bold text-gray-700 hover:text-indigo-600 transition">View Details →</a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" class="w-20 mx-auto opacity-20 mb-4">
                <p class="text-gray-400 italic">No projects found. Time to add some competitors!</p>
            </div>
        @endforelse
    </div>
</div>