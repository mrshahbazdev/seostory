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

        $competitor = $this->project->competitors()->create([
            'name' => $this->comp_name,
            'website_url' => $this->comp_url,
            'status' => 'pending'
        ]);

        try {
            // 1. Fetching logic run karein
            $fetchJob = new \App\Jobs\FetchCompetitorContent($competitor);
            $fetchJob->handle();
            
            // 2. Refresh from Database
            $competitor->refresh();

            // 3. Status check karein (kyunki aapka job 'fetching_completed' set karta hai)
            if ($competitor->status === 'fetching_completed' || !empty($competitor->raw_content)) {
                Log::info("Content saved successfully. Triggering Gemini AI...");
                
                $aiJob = new \App\Jobs\AnalyzeCompetitorAI($competitor);
                $aiJob->handle();
                
                Log::info("AI Analysis completed for: " . $competitor->name);
            } else {
                Log::warning("Fetch finished but status is: " . $competitor->status);
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