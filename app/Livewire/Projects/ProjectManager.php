<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; // Token generation ke liye

class ProjectManager extends Component
{
    public $name, $url;
    public $showCreateForm = false; 

    /**
     * Naya Project Create Karne ki Logic
     */
    public function createProject()
    {
        $this->validate([
            'name' => 'required|min:3',
            // 'unique:projects,url' ka matlab hai ke projects table ke url column mein ye unique ho
            'url' => 'required|url|unique:projects,url', 
        ], [
            // Custom error message taake user ko samajh aaye
            'url.unique' => 'This project URL already exists. Please use a different URL.',
        ]);

        $token = 'seostory_' . bin2hex(random_bytes(16));

        Project::create([
            'name' => $this->name,
            'url' => $this->url,
            'team_id' => Auth::user()->current_team_id,
            'verification_token' => $token,
            'is_verified' => false,
        ]);

        $this->reset(['name', 'url', 'showCreateForm']);
        session()->flash('message', 'Project created successfully!');
    }

    /**
     * Dashboard par projects dikhane ke liye
     */
    public function render()
    {
        // Sirf user ki current team ke projects fetch karein
        $projects = Project::where('team_id', Auth::user()->current_team_id)
                    ->latest()
                    ->get();

        return view('livewire.projects.project-manager', [
            'projects' => $projects
        ]);
    }
}