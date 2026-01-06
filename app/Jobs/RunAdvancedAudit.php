<?php

namespace App\Jobs;

use App\Models\ProjectPage;
use App\Models\Audit;
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
                'User-Agent' => 'SEOsStory-SeobilityBot/3.0',
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

            // 1. TECHNOLOGY & META PILLAR
            $technical = [
                'status_code' => $response->status(),
                'server' => $response->header('Server'),
                'is_compressed' => !empty($response->header('Content-Encoding')),
                'load_time' => $loadTime,
                'content_type' => $response->header('Content-Type'),
            ];

            $seo = [
                'title' => trim($dom->getElementsByTagName('title')->item(0)?->nodeValue ?? ''),
                'description' => $xpath->query('//meta[@name="description"]/@content')->item(0)?->nodeValue,
                'canonical' => $xpath->query('//link[@rel="canonical"]/@href')->item(0)?->nodeValue,
                'robots' => $xpath->query('//meta[@name="robots"]/@content')->item(0)?->nodeValue,
            ];

            // 2. STRUCTURE PILLAR (Links & Depth)
            $path = parse_url($url, PHP_URL_PATH);
            $depth = ($path == '/' || $path == '') ? 0 : count(explode('/', trim($path, '/')));
            
            $internalLinks = $xpath->query('//a[starts-with(@href, "/") or contains(@href, "' . parse_url($url, PHP_URL_HOST) . '")]');
            $externalLinks = $xpath->query('//a[starts-with(@href, "http") and not(contains(@href, "' . parse_url($url, PHP_URL_HOST) . '"))]');

            $structure = [
                'depth' => $depth,
                'internal_count' => $internalLinks->length,
                'external_count' => $externalLinks->length,
                'h1' => $this->getTagsArray($dom, 'h1'),
                'h2' => $this->getTagsArray($dom, 'h2'),
            ];

            // 3. CONTENT PILLAR
            $cleanText = strip_tags($html);
            $wordCount = str_word_count($cleanText);
            
            // Keyword Consistency (Title keywords body mein hain?)
            $titleWords = explode(' ', strtolower(preg_replace('/[^a-z0-9 ]/i', '', $seo['title'])));
            $foundKeywords = array_filter($titleWords, fn($word) => strlen($word) > 3 && str_contains(strtolower($cleanText), $word));

            $content = [
                'word_count' => $wordCount,
                'kw_consistency_score' => (count($titleWords) > 0) ? round((count($foundKeywords) / count($titleWords)) * 100) : 0,
                'images_total' => $dom->getElementsByTagName('img')->length,
                'images_missing_alt' => $xpath->query('//img[not(@alt) or @alt=""]')->length,
            ];

            // --- SEOBILITY ISSUE LOGIC ---
            $issues = [];
            // Tech Issues
            if ($loadTime > 0.5) $issues[] = ['pillar' => 'tech', 'type' => 'warning', 'msg' => 'Long response time'];
            if (empty($seo['description'])) $issues[] = ['pillar' => 'tech', 'type' => 'critical', 'msg' => 'Meta description missing'];
            if (strlen($seo['title']) > 70) $issues[] = ['pillar' => 'tech', 'type' => 'warning', 'msg' => 'Title too long'];
            
            // Structure Issues
            if (count($structure['h1']) != 1) $issues[] = ['pillar' => 'struct', 'type' => 'critical', 'msg' => 'H1 heading problems'];
            if ($depth > 3) $issues[] = ['pillar' => 'struct', 'type' => 'notice', 'msg' => 'High page depth'];
            
            // Content Issues
            if ($wordCount < 300) $issues[] = ['pillar' => 'content', 'type' => 'warning', 'msg' => 'Thin content (less than 300 words)'];
            if ($content['kw_consistency_score'] < 50) $issues[] = ['pillar' => 'content', 'type' => 'notice', 'msg' => 'Title keywords not used in text'];

            // Individual Pillar Scores
            $pillarScores = $this->calculatePillarScores($issues);

            // 4. Update Page
            $this->page->update([
                'title' => $seo['title'],
                'word_count' => $wordCount,
                'load_time' => $loadTime,
                'health_score' => round(array_sum($pillarScores) / 3),
                'full_audit_data' => json_encode([
                    'technical' => $technical,
                    'seo' => $seo,
                    'structure' => $structure,
                    'content' => $content,
                    'issues' => $issues,
                    'pillar_scores' => $pillarScores
                ]),
                'status' => 'audited'
            ]);

            // 🌟 5. Sync with Audit Summary
            $this->syncAuditProgress();

        } catch (\Exception $e) {
            Log::error("Postmortem Failed for " . $this->page->url . " : " . $e->getMessage());
            $this->page->update(['status' => 'failed']);
        }
    }

    private function calculatePillarScores($issues)
    {
        $scores = ['tech' => 100, 'struct' => 100, 'content' => 100];
        foreach ($issues as $issue) {
            $deduction = ($issue['type'] == 'critical') ? 20 : (($issue['type'] == 'warning') ? 10 : 5);
            $scores[$issue['pillar']] -= $deduction;
        }
        return array_map(fn($s) => max(0, $s), $scores);
    }

    private function syncAuditProgress()
    {
        $auditId = $this->page->audit_id;
        if (!$auditId) return;

        $pages = ProjectPage::where('audit_id', $auditId)->where('status', 'audited')->get();
        if ($pages->isEmpty()) return;

        // Pillar Averages
        $techAvg = $pages->avg(fn($p) => json_decode($p->full_audit_data)->pillar_scores->tech);
        $structAvg = $pages->avg(fn($p) => json_decode($p->full_audit_data)->pillar_scores->struct);
        $contentAvg = $pages->avg(fn($p) => json_decode($p->full_audit_data)->pillar_scores->content);

        // Meta Statistics (Seobility Table)
        $duplicateTitles = $pages->groupBy('title')->filter(fn($g) => $g->count() > 1)->flatten()->count();
        $slowPages = $pages->where('load_time', '>', 0.5)->count();

        Audit::where('id', $auditId)->update([
            'score_tech' => round($techAvg),
            'score_structure' => round($structAvg),
            'score_content' => round($contentAvg),
            'overall_health_score' => round(($techAvg + $structAvg + $contentAvg) / 3),
            'pages_scanned' => $pages->count(),
            'tech_meta_data' => json_encode([
                'duplicate_titles' => $duplicateTitles,
                'slow_pages' => $slowPages,
                'avg_load_time' => round($pages->avg('load_time'), 2),
                'status_200' => $pages->count() // Add logic for other codes if needed
            ]),
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
}