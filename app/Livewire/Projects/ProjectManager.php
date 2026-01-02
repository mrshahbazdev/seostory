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
            // Agat Jetstream Teams hai toh ye line lazmi hai:
            'team_id' => Auth::user()->current_team_id, 
        ]);

        $this->reset(['name', 'url', 'showCreateForm']);
        session()->flash('message', 'Project created successfully!');
    }

    public function render()
    {
        return view('livewire.projects.project-manager', [
            'projects' => Project::where('team_id', Auth::user()->current_team_id)
                                ->latest()
                                ->get()
        ]);
    }
}