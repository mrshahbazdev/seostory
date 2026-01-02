<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectManager extends Component
{
    public $name, $url;
    
    // MISSING PROPERTY ADD KAREIN:
    public $showCreateForm = false; 

    public function createProject()
    {
        $this->validate([
            'name' => 'required|min:3',
            'url' => 'required|url',
        ]);

        Project::create([
            'name' => $this->name,
            'url' => $this->url,
            'user_id' => Auth::id(),
        ]);

        $this->reset(['name', 'url', 'showCreateForm']);
        
        // Success message (Optional)
        session()->flash('message', 'Project created successfully!');
    }

    public function render()
    {
        return view('livewire.projects.project-manager', [
            'projects' => Auth::user()->projects()->latest()->get()
        ]);
    }
}