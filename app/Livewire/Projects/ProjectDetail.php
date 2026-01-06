<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\Project;
use App\Models\Competitor;
use App\Models\Audit;
use App\Models\ProjectPage;
use App\Models\CompetitorPage;
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
    
    // UI States & Navigation
    public $currentView = 'overview'; // Seobility Pillar Switcher
    public $showAnalysisModal = false;
    public $activeAnalysis = '';
    public $showAuditModal = false;
    public $selectedAudit = null;
    public $showPageDetailModal = false;
    public $activePageData = null;
    public $showCrawlerModal = false;

    // Drill-down Drill (Drill into specific issues)
    public $activeIssueFilter = null;
    public $filteredPages = [];

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Switch between Pillars (Overview, Tech, Structure, Content)
     */
    public function setView($view)
    {
        $this->currentView = $view;
        $this->resetDrillDown();
    }

    public function resetDrillDown()
    {
        $this->activeIssueFilter = null;
        $this->filteredPages = [];
    }

    /**
     * 🕵️ Drill-down Analysis: Shows the list of pages for a specific issue
     */
    public function showIssueDetails($filter)
    {
        $this->activeIssueFilter = $filter;
        $currentAudit = $this->project->audits()->latest()->first();

        if (!$currentAudit) return;

        $query = ProjectPage::where('audit_id', $currentAudit->id);

        // Seobility Logic Filter
        switch ($filter) {
            case 'duplicate_titles':
                $duplicates = ProjectPage::where('audit_id', $currentAudit->id)
                    ->select('title')
                    ->groupBy('title')
                    ->havingRaw('count(*) > 1')
                    ->pluck('title');
                $this->filteredPages = $query->whereIn('title', $duplicates)->get();
                break;

            case 'problematic_h1':
                $this->filteredPages = $query->get()->filter(function($p) {
                    $data = json_decode($p->full_audit_data, true);
                    return count($data['structure']['h1'] ?? []) != 1;
                });
                break;

            case 'slow_pages':
                $this->filteredPages = $query->where('load_time', '>', 0.5)->get();
                break;

            case 'missing_descriptions':
                $this->filteredPages = $query->get()->filter(function($p) {
                    $data = json_decode($p->full_audit_data, true);
                    return empty($data['seo']['description']);
                });
                break;

            default:
                $this->filteredPages = $query->latest()->take(20)->get();
                break;
        }
    }

    /**
     * ⚡ Start Full Website Audit (Deep Scraper)
     */
    public function startSelfAudit()
    {
        // 1. Create a historical record
        $audit = Audit::create([
            'project_id' => $this->project->id,
            'type' => 'self',
            'status' => 'processing',
            'overall_health_score' => 0,
            'score_tech' => 0,
            'score_structure' => 0,
            'score_content' => 0,
        ]);

        // 2. Dispatch the multi-threaded crawler
        ExtractInternalLinks::dispatch($this->project, $this->project->url, $audit->id);

        $this->dispatch('notify', 'Deep Postmortem engine initialized!');
    }

    /**
     * 🗂️ View Detailed Report of a specific past audit
     */
    public function viewAudit($auditId)
    {
        $this->selectedAudit = Audit::with('projectPages')->findOrFail($auditId);
        $this->showAuditModal = true;
    }

    /**
     * 🔬 Inspect a single page's "X-Ray"
     */
    public function inspectPage($pageId)
    {
        // Pehle purana data saaf karein taake user ko purana data nazar na aaye
        $this->activePageData = null;
        
        $page = \App\Models\ProjectPage::findOrFail($pageId);
        
        // JSON decode kar ke check karein ke data mojud hai
        $auditData = json_decode($page->full_audit_data, true);
        
        if(!$auditData) {
            $this->dispatch('notify', 'No detailed data found for this page.');
            return;
        }

        $this->activePageData = $auditData;
        
        // Basic info lazmi add karein jo Modal mein use hoti hai
        $this->activePageData['summary'] = [
            'url' => $page->url,
            'title' => $page->title,
            'health' => $page->health_score,
            'load_time' => $page->load_time
        ];

        $this->showPageDetailModal = true;
    }

    /**
     * 🔐 Site Ownership Verification
     */
    public function verifySite()
    {
        $url = rtrim($this->project->url, '/');
        $token = $this->project->verification_token;

        try {
            $response = Http::timeout(15)->get($url);
            if ($response->failed()) {
                session()->flash('error', 'Target site is unreachable.');
                return;
            }

            $dom = new \DOMDocument();
            @$dom->loadHTML(mb_convert_encoding($response->body(), 'HTML-ENTITIES', 'UTF-8'));
            $metas = $dom->getElementsByTagName('meta');
            
            $verified = false;
            foreach ($metas as $meta) {
                if ($meta->getAttribute('name') === 'seostory-verify' && 
                    $meta->getAttribute('content') === $token) {
                    $verified = true;
                    break;
                }
            }

            if ($verified) {
                $this->project->update(['is_verified' => true, 'verified_at' => now()]);
                $this->dispatch('notify', 'Verification Successful!'); 
            } else {
                session()->flash('error', 'Meta tag not found in head.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Network error during verification.');
        }
    }

    /**
     * 🕵️ Competitor Tracking
     */
    public function addCompetitor()
    {
        $this->validate([
            'comp_name' => 'required|min:2',
            'comp_url' => 'required|url',
        ]);

        $competitor = $this->project->competitors()->create([
            'name' => $this->comp_name,
            'website_url' => rtrim($this->comp_url, '/'),
            'status' => 'tracking'
        ]);

        FetchCompetitorContent::dispatch($competitor, false);
        $this->reset(['comp_name', 'comp_url']);
        $this->dispatch('notify', 'Competitor Spy engine deployed!');
    }

    public function render()
    {
        // For live crawling monitoring
        $activeAudit = $this->project->audits()->where('status', 'processing')->latest()->first();
        
        $livePages = $activeAudit 
            ? ProjectPage::where('audit_id', $activeAudit->id)->latest()->take(10)->get() 
            : [];

        return view('livewire.projects.project-detail', [
            'competitors' => $this->project->competitors()->latest()->get(),
            // Main History List
            'audits' => $this->project->audits()->where('type', 'self')->latest()->take(10)->get(),
            'activeAudit' => $activeAudit,
            'livePages' => $livePages
        ])->layout('layouts.app');
    }
}