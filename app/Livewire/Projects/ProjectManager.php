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

        // 1. Unique Verification Token banayyein
        // Hum random string use kar rahe hain jo user meta tag mein daalega
        $verificationToken = 'seostory_' . Str::random(32);

        // 2. Project Create Karein
        Project::create([
            'name' => $this->name,
            'url' => $this->url,
            'user_id' => Auth::id(),
            'team_id' => Auth::user()->current_team_id, 
            'verification_token' => $verificationToken, // Naya Column
            'is_verified' => false, // Default false
        ]);

        // 3. Form reset karein aur message dikhayein
        $this->reset(['name', 'url', 'showCreateForm']);
        session()->flash('message', 'Project created successfully! Please verify ownership to start deep crawl.');
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