<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\Project;
use App\Models\Competitor;
use App\Jobs\FetchCompetitorContent;
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

        $competitor = $this->project->competitors()->create([
            'name' => $this->comp_name,
            'website_url' => $this->comp_url,
            'status' => 'pending'
        ]);

        // Background Job ko queue mein dalna
        FetchCompetitorContent::dispatch($competitor);

        $this->reset(['comp_name', 'comp_url']);
        session()->flash('message', 'Competitor added and analysis started!');
    }

    public function render()
    {
        return view('livewire.projects.project-detail', [
            'competitors' => $this->project->competitors()->latest()->get()
        ])->layout('layouts.app');
    }
}