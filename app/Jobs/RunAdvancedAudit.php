<?php

namespace App\Jobs;

use App\Models\ProjectPage;
use App\Models\Audit; // Naya Import
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RunAdvancedAudit implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public ProjectPage $page) {}

    public function handle(): void
    {
        try {
            $url = $this->page->url;
            $startTime = microtime(true);

            $response = Http::withHeaders([
                'User-Agent' => 'SEOsStory-Postmortem-Bot/2.0',
                'Accept-Encoding' => 'gzip, deflate, br',
            ])->timeout(30)->get($url);

            $endTime = microtime(true);
            $loadTime = round($endTime - $startTime, 3);

            if ($response->failed()) {
                $this->page->update(['status' => 'failed']);
                return;
            }

            $html = $response->body();
            $dom = new \DOMDocument();
            @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
            $xpath = new \DOMXPath($dom);

            // --- Analysis Logic (Same as your code) ---
            $technical = [
                'status_code' => $response->status(),
                'server' => $response->header('Server'),
                'is_compressed' => !empty($response->header('Content-Encoding')),
                'load_time_sec' => $loadTime,
            ];

            $seo = [
                'title' => $dom->getElementsByTagName('title')->item(0)?->nodeValue,
                'description' => $xpath->query('//meta[@name="description"]/@content')->item(0)?->nodeValue,
                'canonical' => $xpath->query('//link[@rel="canonical"]/@href')->item(0)?->nodeValue,
            ];

            $content = [
                'word_count' => str_word_count(strip_tags($html)),
                'h1' => $this->getTagsArray($dom, 'h1'),
                'images_missing_alt' => $xpath->query('//img[not(@alt) or @alt=""]')->length,
            ];

            // Issue Detection
            $issues = [];
            if ($loadTime > 2) $issues[] = ['type' => 'warning', 'msg' => 'Slow Response Time (>2s)'];
            if (empty($seo['description'])) $issues[] = ['type' => 'critical', 'msg' => 'Meta Description Missing'];
            if (count($content['h1']) > 1) $issues[] = ['type' => 'warning', 'msg' => 'Multiple H1 tags detected'];
            if (empty($seo['title'])) $issues[] = ['type' => 'critical', 'msg' => 'Title Tag Missing'];

            // 1. Save Page Data
            $this->page->update([
                'title' => $seo['title'],
                'word_count' => $content['word_count'],
                'load_time' => $loadTime,
                'full_audit_data' => json_encode([
                    'technical' => $technical,
                    'seo' => $seo,
                    'content' => $content,
                    'issues' => $issues
                ]),
                'health_score' => $this->calculateHealthScore($issues),
                'status' => 'audited'
            ]);

            // 🌟 2. THE FIX: Sync with Main Audit Table
            $this->syncAuditProgress();

        } catch (\Exception $e) {
            Log::error("Postmortem Failed: " . $e->getMessage());
            $this->page->update(['status' => 'failed']);
        }
    }

    /**
     * Ye function har page audit ke baad main report ka score calculate karega
     */
    private function syncAuditProgress()
    {
        $auditId = $this->page->audit_id;
        if (!$auditId) return;

        // Is scan ke saare pages ka average score nikalain
        $avgScore = ProjectPage::where('audit_id', $auditId)
            ->where('status', 'audited')
            ->avg('health_score');

        // Critical issues ka total count
        $totalCritical = ProjectPage::where('audit_id', $auditId)->get()->sum(function($p) {
            $data = json_decode($p->full_audit_data, true);
            return collect($data['issues'] ?? [])->where('type', 'critical')->count();
        });

        // Main Audit Record update karein
        Audit::where('id', $auditId)->update([
            'overall_health_score' => round($avgScore ?? 0),
            'critical_issues' => $totalCritical,
            'pages_scanned' => ProjectPage::where('audit_id', $auditId)->count(),
            // Agar processing ho rahi hai toh status update karein
            'status' => 'completed' 
        ]);
    }

    private function getTagsArray($dom, $tag) {
        $tags = [];
        foreach ($dom->getElementsByTagName($tag) as $node) {
            $tags[] = trim($node->nodeValue);
        }
        return $tags;
    }

    private function calculateHealthScore($issues) {
        $score = 100;
        foreach ($issues as $issue) {
            if ($issue['type'] == 'critical') $score -= 20;
            if ($issue['type'] == 'warning') $score -= 10;
            if ($issue['type'] == 'notice') $score -= 5;
        }
        return max(0, $score);
    }
}