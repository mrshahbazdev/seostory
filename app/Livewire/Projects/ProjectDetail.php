<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\Project;
use App\Models\Competitor;
use App\Models\CompetitorPage;
use App\Models\Audit;
use App\Models\ProjectPage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Jobs\ExtractInternalLinks;
use App\Jobs\FetchCompetitorContent;
use App\Jobs\AnalyzeCompetitorAI;

class ProjectDetail extends Component
{
    public Project $project;
    
    // Form Inputs
    public $comp_name, $comp_url;
    public $sub_page_url; 
    
    // UI States
    public $showAnalysisModal = false;
    public $activeAnalysis = '';
    public $showAuditModal = false;
    public $selectedAudit = null;
    public $showPageDetailModal = false;
    public $activePageData = null;
    public $showCrawlerModal = false;
    public function mount(Project $project)
    {
        $this->project = $project;
    }
    public function openCrawlerStatus()
    {
        $this->showCrawlerModal = true;
    }
    public function inspectPage($pageId)
    {
        $page = \App\Models\ProjectPage::findOrFail($pageId);
        $this->activePageData = json_decode($page->full_audit_data, true);
        // Hum page ki basic info bhi add kar dete hain summary ke liye
        $this->activePageData['summary'] = [
            'url' => $page->url,
            'title' => $page->title,
            'health' => $page->health_score,
            'load_time' => $page->load_time
        ];
        $this->showPageDetailModal = true;
    }
    /**
     * 🔐 Site Verification Logic
     */
    public function verifySite()
    {
        $url = $this->project->url;
        $token = $this->project->verification_token;

        try {
            $response = Http::timeout(15)
                ->withHeaders(['User-Agent' => 'SEOsStory-Audit-Bot/1.0'])
                ->get($url);

            if ($response->failed()) {
                session()->flash('error', 'Site unreachable. Please check if your URL is correct.');
                return;
            }

            $html = $response->body();
            $dom = new \DOMDocument();
            @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
            $head = $dom->getElementsByTagName('head')->item(0);

            if (!$head) {
                session()->flash('error', 'Technical Error: Could not find <head> section.');
                return;
            }

            $verified = false;
            $metas = $head->getElementsByTagName('meta');
            foreach ($metas as $meta) {
                if ($meta->getAttribute('name') === 'seostory-verify' && 
                    $meta->getAttribute('content') === $token) {
                    $verified = true;
                    break;
                }
            }

            if ($verified) {
                $this->project->update([
                    'is_verified' => true,
                    'verified_at' => now(),
                ]);
                $this->dispatch('notify', 'Website Verified Successfully!'); 
            } else {
                session()->flash('error', 'Verification failed: Meta tag missing from <head>.');
            }

        } catch (\Exception $e) {
            Log::error("Verification Error: " . $e->getMessage());
            session()->flash('error', 'Connection error. Make sure your site is public.');
        }
    }

    /**
     * ⚡ START SELF-AUDIT (Deep Scan of My Own Site)
     * Isko user manually click karega taake bar bar credit/resource zaya na ho.
     */
    public function startSelfAudit()
    {
        // 1. Create a historical Audit record
        $audit = Audit::create([
            'project_id' => $this->project->id,
            'type' => 'self',
            'status' => 'processing',
            'overall_health_score' => 0
        ]);

        // 2. Dispatch Background Crawler (Browser close safe)
        ExtractInternalLinks::dispatch($this->project, $this->project->url, $audit->id);

        $this->dispatch('notify', 'Deep Audit started! You can safely close the browser.');
    }

    /**
     * 🕵️ ADD COMPETITOR (Main Domain Scan)
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

        // Background job for scraping competitor
        FetchCompetitorContent::dispatch($competitor, false);

        $this->reset(['comp_name', 'comp_url']);
        $this->dispatch('notify', 'Competitor added and tracking deployed!');
    }

    /**
     * 🕸️ SCAN COMPETITOR SUB-PAGE
     */
    public function scanSubPage($competitorId)
    {
        $this->validate(['sub_page_url' => 'required|url']);

        $page = CompetitorPage::create([
            'competitor_id' => $competitorId,
            'url' => $this->sub_page_url,
            'status' => 'pending'
        ]);

        FetchCompetitorContent::dispatch($page, true);

        $this->reset('sub_page_url');
        $this->dispatch('notify', 'Sub-page audit queued!');
    }

    /**
     * 🤖 RUN AI ANALYSIS (Competitor Insights)
     */
    public function runAI($id)
    {
        $competitor = Competitor::findOrFail($id);
        $competitor->update(['status' => 'analyzing']);

        AnalyzeCompetitorAI::dispatch($competitor);
        $this->dispatch('notify', 'AI Engine is crunching competitor data...');
    }

    public function openAnalysis($id)
    {
        $competitor = Competitor::findOrFail($id);
        $this->activeAnalysis = $competitor->metadata['analysis'] ?? 'No analysis available yet.';
        $this->showAnalysisModal = true;
    }
    public function viewAudit($auditId)
    {
        // Specific audit load karein uske pages ke sath
        $this->selectedAudit = Audit::with('projectPages')->findOrFail($auditId);
        $this->showAuditModal = true;
    }


    public function render()
    {
        // Latest Audit ID nikalain jo abhi processing mein hai
        $activeAudit = $this->project->audits()->where('status', 'processing')->latest()->first();
        
        $livePages = [];
        if ($activeAudit) {
            $livePages = \App\Models\ProjectPage::where('audit_id', $activeAudit->id)
                            ->latest()
                            ->take(10)
                            ->get();
        }

        return view('livewire.projects.project-detail', [
            'competitors' => $this->project->competitors()->latest()->get(),
            'audits' => $this->project->audits()->where('type', 'self')->latest()->take(10)->get(),
            'livePages' => $livePages, // Ye live feed ke liye hai
            'activeAudit' => $activeAudit
        ])->layout('layouts.app');
    }
}