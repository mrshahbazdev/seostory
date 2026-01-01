<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectManager extends Component
{
    public $name;
    public $url;

    protected $rules = [
        'name' => 'required|min:3|max:50',
        'url' => 'nullable|url',
    ];

    public function createProject()
    {
        $this->validate();

        // Har project current team (workspace) se link hoga
        Auth::user()->currentTeam->projects()->create([
            'name' => $this->name,
            'url' => $this->url,
        ]);

        $this->reset(['name', 'url']);
        session()->flash('message', 'Project successfully created!');
    }

    public function render()
    {
        return view('livewire.projects.project-manager', [
            'projects' => Auth::user()->currentTeam->projects()->latest()->get()
        ]);
    }
}