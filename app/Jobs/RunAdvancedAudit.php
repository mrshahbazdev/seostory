<?php

namespace App\Jobs;

use App\Models\ProjectPage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class RunAdvancedAudit implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public ProjectPage $page) {}

    public function handle(): void
    {
        try {
            $url = $this->page->url;
            $startTime = microtime(true);

            // 1. Fetch Page with Heavy Headers
            $response = Http::withHeaders([
                'User-Agent' => 'SEOsStory-Postmortem-Bot/2.0',
                'Accept-Encoding' => 'gzip, deflate, br',
            ])->timeout(30)->get($url);

            $endTime = microtime(true);
            $loadTime = round($endTime - $startTime, 3); // TTFB / Load Time

            if ($response->failed()) {
                $this->page->update(['status' => 'failed']);
                return;
            }

            $html = $response->body();
            $dom = new \DOMDocument();
            @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
            $xpath = new \DOMXPath($dom);

            // --- 🧪 THE POSTMORTEM ANALYSIS ---

            // A. Technical Audit
            $technical = [
                'status_code' => $response->status(),
                'server' => $response->header('Server'),
                'content_type' => $response->header('Content-Type'),
                'is_compressed' => !empty($response->header('Content-Encoding')),
                'load_time_sec' => $loadTime,
            ];

            // B. Advanced SEO Tags
            $seo = [
                'title' => $dom->getElementsByTagName('title')->item(0)?->nodeValue,
                'canonical' => $xpath->query('//link[@rel="canonical"]/@href')->item(0)?->nodeValue,
                'robots' => $xpath->query('//meta[@name="robots"]/@content')->item(0)?->nodeValue,
                'description' => $xpath->query('//meta[@name="description"]/@content')->item(0)?->nodeValue,
                'favicon' => $xpath->query('//link[@rel="icon"]/@href')->item(0)?->nodeValue ?? $xpath->query('//link[@rel="shortcut icon"]/@href')->item(0)?->nodeValue,
            ];

            // C. Schema Markup Detection (JSON-LD)
            $schemas = [];
            foreach ($xpath->query('//script[@type="application/ld+json"]') as $script) {
                $decoded = json_decode($script->nodeValue, true);
                if ($decoded) $schemas[] = $decoded;
            }

            // D. Social Graph (Open Graph / Twitter)
            $social = [
                'og_title' => $xpath->query('//meta[@property="og:title"]/@content')->item(0)?->nodeValue,
                'og_image' => $xpath->query('//meta[@property="og:image"]/@content')->item(0)?->nodeValue,
                'twitter_card' => $xpath->query('//meta[@name="twitter:card"]/@content')->item(0)?->nodeValue,
            ];

            // E. Content Structure Postmortem
            $content = [
                'word_count' => str_word_count(strip_tags($html)),
                'h1' => $this->getTagsArray($dom, 'h1'),
                'h2' => $this->getTagsArray($dom, 'h2'),
                'images_total' => $dom->getElementsByTagName('img')->length,
                'images_missing_alt' => $xpath->query('//img[not(@alt) or @alt=""]')->length,
                'internal_links' => $xpath->query('//a[starts-with(@href, "/")]')->length,
                'external_links' => $xpath->query('//a[starts-with(@href, "http") and not(contains(@href, "' . parse_url($url, PHP_URL_HOST) . '"))]')->length,
            ];

            // F. Issue Detection Logic (Postmortem Summary)
            $issues = [];
            if ($loadTime > 2) $issues[] = ['type' => 'warning', 'msg' => 'Slow Response Time (>2s)'];
            if (empty($seo['description'])) $issues[] = ['type' => 'critical', 'msg' => 'Meta Description Missing'];
            if (count($content['h1']) > 1) $issues[] = ['type' => 'warning', 'msg' => 'Multiple H1 tags detected'];
            if (empty($seo['canonical'])) $issues[] = ['type' => 'notice', 'msg' => 'Canonical Tag Missing'];

            // Save Everything to Database
            $this->page->update([
                'title' => $seo['title'],
                'word_count' => $content['word_count'],
                'load_time' => $loadTime,
                'schema_types' => json_encode(array_column($schemas, '@type')),
                'full_audit_data' => json_encode([
                    'technical' => $technical,
                    'seo' => $seo,
                    'social' => $social,
                    'content' => $content,
                    'schemas' => $schemas,
                    'issues' => $issues
                ]),
                'health_score' => $this->calculateHealthScore($issues),
                'status' => 'audited'
            ]);

        } catch (\Exception $e) {
            \Log::error("Postmortem Failed for " . $this->page->url . " : " . $e->getMessage());
            $this->page->update(['status' => 'failed']);
        }
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