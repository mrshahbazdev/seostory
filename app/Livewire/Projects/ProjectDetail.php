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

        // Queue ko bypass karke DIRECT fetch aur analyze karein
        try {
            // 1. Fetching Start
            $fetchJob = new \App\Jobs\FetchCompetitorContent($competitor);
            $fetchJob->handle();

            // 2. AI Analysis Start (Refresh model to get fetched content)
            $competitor->refresh();
            if ($competitor->raw_content) {
                $aiJob = new \App\Jobs\AnalyzeCompetitorAI($competitor);
                $aiJob->handle();
            }
        } catch (\Exception $e) {
            \Log::error("Direct Run Error: " . $e->getMessage());
        }

        $this->reset(['comp_name', 'comp_url']);
    }

    public function render()
    {
        return view('livewire.projects.project-detail', [
            'competitors' => $this->project->competitors()->latest()->get()
        ])->layout('layouts.app');
    }
}