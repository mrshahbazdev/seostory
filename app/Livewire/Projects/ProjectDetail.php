<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\Project;
use App\Models\Competitor;
use Illuminate\Support\Facades\Log;

class ProjectDetail extends Component
{
    public Project $project;
    public $comp_name, $comp_url;
    
    // Phase 4 Properties
    public $showAnalysisModal = false;
    public $activeAnalysis = '';

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

        // 1. Create record
        $competitor = $this->project->competitors()->create([
            'name' => $this->comp_name,
            'website_url' => $this->comp_url,
            'status' => 'pending'
        ]);

        try {
            // 2. Direct Fetch
            $fetchJob = new \App\Jobs\FetchCompetitorContent($competitor);
            $fetchJob->handle();
            
            // 3. Re-fetch from DB to see if content was saved
            $competitor->refresh();

            if (!empty($competitor->raw_content)) {
                // 4. Direct AI Analysis
                $aiJob = new \App\Jobs\AnalyzeCompetitorAI($competitor);
                $aiJob->handle();
            } else {
                Log::warning("Fetch job finished but raw_content is empty for: " . $competitor->website_url);
            }

        } catch (\Exception $e) {
            Log::error("Direct Run Critical Error: " . $e->getMessage());
            $competitor->update(['status' => 'failed']);
        }

        $this->reset(['comp_name', 'comp_url']);
    }

    public function openAnalysis($id)
    {
        $competitor = Competitor::findOrFail($id);
        $this->activeAnalysis = $competitor->metadata['analysis'] ?? 'No analysis available yet. The scraper might have been blocked.';
        $this->showAnalysisModal = true;
    }

    public function render()
    {
        return view('livewire.projects.project-detail', [
            'competitors' => $this->project->competitors()->latest()->get()
        ])->layout('layouts.app');
    }
}