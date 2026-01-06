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
            'url' => 'required|url',
        ]);

        // 1. Unique Verification Token generate karein
        $verificationToken = 'seostory_' . Str::random(32);

        // 2. Project Create Karein (user_id ko nikal diya hai)
        Project::create([
            'name' => $this->name,
            'url' => $this->url,
            'team_id' => Auth::user()->current_team_id, // Saara link team se hai
            'verification_token' => $verificationToken,
            'is_verified' => false,
        ]);

        // 3. Reset and Flash
        $this->reset(['name', 'url', 'showCreateForm']);
        session()->flash('message', 'Project created! Please verify ownership.');
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