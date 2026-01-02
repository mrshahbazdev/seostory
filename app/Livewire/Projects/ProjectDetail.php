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

        $competitor = $this->project->competitors()->create([
            'name' => $this->comp_name,
            'website_url' => $this->comp_url,
            'status' => 'pending'
        ]);

        try {
            // Step 1: Sirf Scraper chalayein (Technical Audit)
            $fetchJob = new \App\Jobs\FetchCompetitorContent($competitor);
            $fetchJob->handle();
            
            Log::info("Technical Scraping completed for: " . $competitor->name);
        } catch (\Exception $e) {
            Log::error("Scraping Error: " . $e->getMessage());
            $competitor->update(['status' => 'failed']);
        }

        $this->reset(['comp_name', 'comp_url']);
    }

    // YEH HAI WOH MISSING FUNCTION:
    public function runAI($id)
    {
        $competitor = Competitor::findOrFail($id);
        
        // Status update taake UI par animation dikhe
        $competitor->update(['status' => 'analyzing']);

        try {
            // Step 2: Manually trigger ChatGPT Analysis
            $aiJob = new \App\Jobs\AnalyzeCompetitorAI($competitor);
            $aiJob->handle();
            
            Log::info("AI Analysis manually triggered and finished for: " . $competitor->name);
        } catch (\Exception $e) {
            Log::error("AI Manual Run Error: " . $e->getMessage());
            $competitor->update(['status' => 'failed']);
        }
    }

    public function openAnalysis($id)
    {
        $competitor = Competitor::findOrFail($id);
        $this->activeAnalysis = $competitor->metadata['analysis'] ?? 'No analysis available yet.';
        $this->showAnalysisModal = true;
    }

    public function render()
    {
        return view('livewire.projects.project-detail', [
            'competitors' => $this->project->competitors()->latest()->get()
        ])->layout('layouts.app');
    }
}