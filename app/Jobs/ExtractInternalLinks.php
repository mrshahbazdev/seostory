<?php

namespace App\Jobs;

use App\Models\Project;
use App\Models\ProjectPage;
use App\Models\Audit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExtractInternalLinks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Timeout ko bara rakhein taake agar site slow ho toh job fail na ho
    public $timeout = 120;

    /**
     * @param Project $project: Kaunsa project audit ho raha hai
     * @param string|null $url: Kaunsa specific page crawl karna hai (Default Home)
     * @param int|null $auditId: Is scan ki unique Audit ID history ke liye
     */
    public function __construct(
        public Project $project, 
        public $url = null, 
        public $auditId = null
    ) {}

    public function handle(): void
    {
        $targetUrl = $this->url ?? $this->project->url;
        $host = parse_url($this->project->url, PHP_URL_HOST);

        try {
            // 1. Page fetch karein
            $response = Http::withHeaders([
                'User-Agent' => 'SEOsStory-Spider/2.0 (+https://seostory.de)'
            ])->timeout(20)->get($targetUrl);

            if ($response->failed()) return;

            $html = $response->body();
            $dom = new \DOMDocument();
            @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
            $links = $dom->getElementsByTagName('a');

            foreach ($links as $link) {
                $href = $link->getAttribute('href');

                // 2. URL ko normalize karein (Relative ko Full URL banayein)
                $fullUrl = $this->normalizeUrl($href);

                if (!$fullUrl) continue;

                // 3. Check karein ke link usi domain ka hai (Internal Link)
                if (parse_url($fullUrl, PHP_URL_HOST) === $host) {
                    
                    // 4. Database mein save karein (Audit ID ke saath connect karein)
                    // Hum check karte hain ke kya is SPECIFIC AUDIT mein ye page pehle se hai?
                    $page = ProjectPage::firstOrCreate(
                        [
                            'project_id' => $this->project->id, 
                            'audit_id'   => $this->auditId, // Scan History Tracking
                            'url'        => $fullUrl
                        ],
                        ['status' => 'pending']
                    );

                    // 5. Agar naya page mila hai toh uska "Deep Postmortem" shuru karein
                    if ($page->wasRecentlyCreated) {
                        // Background Audit Job trigger karein
                        RunAdvancedAudit::dispatch($page);
                        
                        // 6. RECURSION: Is page ke andar mazeed links dhoondne ke liye naya loop
                        // Limit lagana zaroori hai (e.g., 200 pages max) taake infinite loop na banay
                        $currentPagesCount = ProjectPage::where('audit_id', $this->auditId)->count();
                        
                        if ($currentPagesCount < 200) {
                            // Nayi link extraction job queue mein dalain (2 sec delay ke sath taake server par load na paray)
                            self::dispatch($this->project, $fullUrl, $this->auditId)->delay(now()->addSeconds(2));
                        }
                    }
                }
            }

            // 7. Audit Summary Update karein
            $this->updateAuditProgress();

        } catch (\Exception $e) {
            Log::error("Spider Error on $targetUrl: " . $e->getMessage());
        }
    }

    /**
     * URL ko saaf aur sahi format mein lane ke liye helper
     */
    private function normalizeUrl($href)
    {
        if (empty($href) || str_starts_with($href, '#') || str_starts_with($href, 'javascript:') || str_starts_with($href, 'tel:') || str_starts_with($href, 'mailto:')) {
            return null;
        }

        // Relative path ko Absolute banayein
        if (str_starts_with($href, '/')) {
            $href = rtrim($this->project->url, '/') . $href;
        }

        // Sirf Valid HTTP/S URLs rakhein
        if (!filter_var($href, FILTER_VALIDATE_URL)) return null;

        // URL se anchors (#) aur trailing slashes nikal dein taake duplicate na hon
        $url = explode('#', $href)[0];
        return rtrim($url, '/');
    }

    /**
     * Har page milne ke baad Audit table mein progress update karein
     */
    private function updateAuditProgress()
    {
        if ($this->auditId) {
            $count = ProjectPage::where('audit_id', $this->auditId)->count();
            Audit::where('id', $this->auditId)->update([
                'pages_scanned' => $count,
                'status' => 'crawling'
            ]);
        }
    }
}