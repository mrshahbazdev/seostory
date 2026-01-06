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
        $url = $this->project->url;
        $token = $this->project->verification_token;

        try {
            // 1. Website fetch karein
            $response = \Illuminate\Support\Facades\Http::timeout(15)
                ->withHeaders(['User-Agent' => 'SEOsStory-Audit-Bot/1.0'])
                ->get($url);

            if ($response->failed()) {
                session()->flash('error', 'Site unreachable. Please check if your URL is correct.');
                return;
            }

            $html = $response->body();

            // 2. DOM Document use karein taake exact Head section check ho sake
            $dom = new \DOMDocument();
            @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
            $head = $dom->getElementsByTagName('head')->item(0);

            if (!$head) {
                session()->flash('error', 'Technical Error: Could not find <head> section on your website.');
                return;
            }

            // 3. Head ke andar meta tag dhoondain
            $verified = false;
            $metas = $head->getElementsByTagName('meta');
            
            foreach ($metas as $meta) {
                if ($meta->getAttribute('name') === 'seostory-verify' && 
                    $meta->getAttribute('content') === $token) {
                    $verified = true;
                    break;
                }
            }

            // 4. Final Result Handling
            if ($verified) {
                $this->project->update([
                    'is_verified' => true,
                    'verified_at' => now(),
                ]);
                
                // Success! Page refresh ho jayega aur blur khatam ho jayega
                $this->dispatch('notify', 'Website Verified Successfully!'); 
            } else {
                // SPECIFIC ERROR MESSAGE:
                session()->flash('error', 'Verification failed: Meta tag is missing from your <head> section. Please double-check the token.');
            }

        } catch (\Exception $e) {
            \Log::error("Verification Error: " . $e->getMessage());
            session()->flash('error', 'Something went wrong. Make sure your site is public.');
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