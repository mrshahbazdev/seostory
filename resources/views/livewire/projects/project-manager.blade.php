<div class="space-y-6">
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Project</h3>
        <form wire:submit.prevent="createProject" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" wire:model="name" placeholder="Project Name" 
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            
            <div class="flex-1">
                <input type="text" wire:model="url" placeholder="URL (optional)" 
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                Save Project
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($projects as $project)
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition">
                <div class="flex justify-between items-start">
                    <h4 class="font-bold text-gray-800">{{ $project->name }}</h4>
                    <span class="text-[10px] bg-gray-100 px-2 py-1 rounded text-gray-500">UUID: {{ Str::limit($project->id, 8) }}</span>
                </div>
                <p class="text-sm text-gray-500 mt-1 truncate">{{ $project->url ?? 'No URL provided' }}</p>
                <div class="mt-4 flex items-center text-xs text-gray-400">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ $project->created_at->diffForHumans() }}
                </div>
            </div>
        @empty
            <div class="col-span-full bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl py-12 text-center text-gray-500">
                No projects found. Add your first project above.
            </div>
        @endforelse
    </div>
</div>