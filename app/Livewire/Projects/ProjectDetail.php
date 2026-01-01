<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\Project;
use App\Models\Competitor;

class ProjectDetail extends Component
{
    public Project $project;
    public $comp_name, $comp_url;

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function addCompetitor()
    {
        $this->validate([
            'comp_name' => 'required|min:2',
            'comp_url' => 'required|url',
        ]);

        $this->project->competitors()->create([
            'name' => $this->comp_name,
            'website_url' => $this->comp_url,
        ]);

        $this->reset(['comp_name', 'comp_url']);
        session()->flash('message', 'Competitor added to project!');
    }

    public function render()
    {
        return view('livewire.projects.project-detail', [
            'competitors' => $this->project->competitors()->latest()->get()
        ])->layout('layouts.app');
    }
}