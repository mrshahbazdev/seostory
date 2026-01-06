<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\Project;
use App\Models\Competitor;
use App\Models\CompetitorPage; // Naya Model
use Illuminate\Support\Facades\Log;

class ProjectDetail extends Component
{
    public Project $project;
    public $comp_name, $comp_url;
    
    // Sub-page scanning properties
    public $sub_page_url; 
    public $selectedCompetitorId = null; 

    public $showAnalysisModal = false;
    public $activeAnalysis = '';

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Phase 3: Add Main Competitor (Main Domain)
     */
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
            // Hum job ko bhej rahe hain (False ka matlab hai ye main domain hai)
            $fetchJob = new \App\Jobs\FetchCompetitorContent($competitor, false);
            $fetchJob->handle();
            
            Log::info("Technical Scraping completed for main domain: " . $competitor->name);
        } catch (\Exception $e) {
            Log::error("Scraping Error: " . $e->getMessage());
            $competitor->update(['status' => 'failed']);
        }

        $this->reset(['comp_name', 'comp_url']);
    }

    /**
     * NEW: Scan Specific Sub-Page of a Competitor
     */
    public function scanSubPage($competitorId)
    {
        $this->validate([
            'sub_page_url' => 'required|url',
        ]);

        $competitor = Competitor::findOrFail($competitorId);

        // Sub-page record create karein
        $page = CompetitorPage::create([
            'competitor_id' => $competitorId,
            'url' => $this->sub_page_url,
            'status' => 'pending'
        ]);

        try {
            // True ka matlab hai ye sub-page crawl ho raha hai
            $fetchJob = new \App\Jobs\FetchCompetitorContent($page, true);
            $fetchJob->handle();
            
            $this->reset('sub_page_url');
            Log::info("Sub-page audit finished for: " . $page->url);
        } catch (\Exception $e) {
            Log::error("Sub-page Scan Error: " . $e->getMessage());
            $page->update(['status' => 'failed']);
        }
    }

    /**
     * Phase 4: Run AI Analysis manually
     */
    public function runAI($id)
    {
        $competitor = Competitor::findOrFail($id);
        $competitor->update(['status' => 'analyzing']);

        try {
            $aiJob = new \App\Jobs\AnalyzeCompetitorAI($competitor);
            $aiJob->handle();
            Log::info("AI Analysis finished for: " . $competitor->name);
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
    public function verifySite()
    {
        // 1. Project ki URL aur Token lein
        $url = $this->project->url;
        $token = $this->project->verification_token;

        try {
            // 2. User ki website fetch karein (15 seconds timeout ke sath)
            $response = \Illuminate\Support\Facades\Http::timeout(15)
                ->withHeaders(['User-Agent' => 'SEOsStory-Verification-Bot/1.0'])
                ->get($url);

            if ($response->failed()) {
                session()->flash('error', 'Haqeeqatan site tak nahi pohanch sakay. Check your URL.');
                return;
            }

            $html = $response->body();

            // 3. Meta tag dhoondne ke liye Regex pattern
            // Ye pattern dhoondega: <meta name="seostory-verify" content="TOKEN">
            $pattern = '/<meta[^>]*name=["\']seostory-verify["\'][^>]*content=["\']' . preg_quote($token, '/') . '["\'][^>]*>/i';

            if (preg_match($pattern, $html)) {
                // ✅ Success! Database update karein
                $this->project->update([
                    'is_verified' => true,
                    'verified_at' => now(),
                ]);

                // Success Notification
                $this->dispatch('notify', 'Ownership Verified! Surveillance engine initialized.');
                
                // Foran Crawling shuru karne ke liye function call kar sakte hain
                $this->startInitialAudit(); 
                
            } else {
                // ❌ Tag nahi mila
                session()->flash('error', 'Verification tag nahi mila. Ensure it is in the <head> section.');
            }

        } catch (\Exception $e) {
            \Log::error("Verification Error: " . $e->getMessage());
            session()->flash('error', 'Technical error: Site unreachable or slow.');
        }
    }
    public function render()
    {
        return view('livewire.projects.project-detail', [
            // Eager loading pages taake query fast ho
            'competitors' => $this->project->competitors()->with('pages')->latest()->get()
        ])->layout('layouts.app');
    }
}