<?php

namespace App\Livewire\Audit;

use Livewire\Component;
use App\Models\Audit;
use App\Services\SeoAuditService;
use Illuminate\Http\Request;

class Show extends Component
{
    public $url;
    public $audit;
    public $loading = true;
    public $error = null;

    protected $queryString = ['url'];

    public function mount(Request $request)
    {
        $this->url = $request->query('url');

        if (!$this->url) {
            return redirect()->route('welcome');
        }

        // Check if we just did this audit recently (cache)? 
        // For now, always create new one for "fresh" check
        $this->audit = Audit::create([
            'url' => $this->url,
            'status' => 'pending',
            'type' => 'quick',
        ]);
    }

    public function runAudit(SeoAuditService $service)
    {
        if (!$this->audit)
            return;

        $result = $service->analyze($this->url);

        if ($result['success']) {
            $this->audit->update([
                'status' => 'completed',
                'title' => $result['title'],
                'overall_health_score' => $result['scores']['overall'],
                'score_meta' => $result['scores']['meta'],
                'score_structure' => $result['scores']['structure'],
                'score_tech' => $result['scores']['tech'],
                'tech_meta_data' => $result['details']['meta'],
                'structure_data' => $result['details']['structure'],
                'summary_data' => $result['details']['tech'],
            ]);
        } else {
            $this->audit->update(['status' => 'failed']);
            $this->error = $result['error'] ?? 'Unknown error';
        }

        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.audit.show')->layout('layouts.app');
        // Note: Layout app might have sidebar. 
        // For public tool, we might want a simpler layout or ensure sidebar is hidden/adapted.
        // We'll use 'layouts.app' for now and fix UI if needed.
    }
}
