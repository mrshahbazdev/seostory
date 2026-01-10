<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use DOMDocument;
use DOMXPath;

class SeoAuditService
{
    protected $timeout = 10;
    protected $userAgent = 'SeoStoryBot/1.0 (+http://seostory.ai/bot)';

    public function analyze(string $url): array
    {
        $startTime = microtime(true);

        try {
            $response = Http::withHeaders(['User-Agent' => $this->userAgent])
                ->timeout($this->timeout)
                ->get($url);

            $loadTime = round((microtime(true) - $startTime) * 1000); // ms

            if ($response->failed()) {
                return $this->failedResult($url, "HTTP Error: " . $response->status());
            }

            $html = $response->body();
            $dom = new DOMDocument();
            @$dom->loadHTML($html); // Suppress warnings for malformed HTML
            $xpath = new DOMXPath($dom);

            // 1. Meta Analysis
            $meta = $this->analyzeMeta($xpath, $dom);

            // 2. Structure & Content
            $structure = $this->analyzeStructure($xpath, $dom, $html);

            // 3. Technical
            $tech = $this->analyzeTechnical($response, $loadTime);

            // Calculate Scores
            $scoreMeta = $this->calculateScore($meta['issues']);
            $scoreStructure = $this->calculateScore($structure['issues']);
            $scoreTech = $this->calculateScore($tech['issues']);

            $overallScore = round(($scoreMeta + $scoreStructure + $scoreTech) / 3);

            return [
                'success' => true,
                'url' => $url,
                'title' => $meta['data']['title'] ?? '',
                'scores' => [
                    'overall' => $overallScore,
                    'meta' => $scoreMeta,
                    'structure' => $scoreStructure,
                    'tech' => $scoreTech,
                    'content' => 0, // Placeholder for future word count analysis
                ],
                'details' => [
                    'meta' => $meta,
                    'structure' => $structure,
                    'tech' => $tech,
                ]
            ];

        } catch (\Exception $e) {
            return $this->failedResult($url, $e->getMessage());
        }
    }

    private function analyzeMeta(DOMXPath $xpath, DOMDocument $dom): array
    {
        $issues = [];
        $data = [];

        // Title
        $nodes = $xpath->query('//title');
        $title = $nodes->length > 0 ? $nodes->item(0)->textContent : null;
        $data['title'] = $title;

        if (!$title) {
            $issues[] = ['type' => 'critical', 'message' => 'Missing Title Tag'];
        } elseif (strlen($title) < 10) {
            $issues[] = ['type' => 'warning', 'message' => 'Title is too short (< 10 chars)'];
        } elseif (strlen($title) > 60) {
            $issues[] = ['type' => 'warning', 'message' => 'Title is too long (> 60 chars)'];
        }

        // Meta Description
        $descNode = $xpath->query('//meta[@name="description"]/@content');
        $description = $descNode->length > 0 ? $descNode->item(0)->nodeValue : null;
        $data['description'] = $description;

        if (!$description) {
            $issues[] = ['type' => 'critical', 'message' => 'Missing Meta Description'];
        } elseif (strlen($description) < 50) {
            $issues[] = ['type' => 'warning', 'message' => 'Meta Description is too short (< 50 chars)'];
        } elseif (strlen($description) > 160) {
            $issues[] = ['type' => 'warning', 'message' => 'Meta Description is too long (> 160 chars)'];
        }

        // Viewport
        $viewport = $xpath->query('//meta[@name="viewport"]');
        if ($viewport->length === 0) {
            $issues[] = ['type' => 'critical', 'message' => 'Missing Viewport Meta Tag (Not Mobile Friendly)'];
        }

        return ['data' => $data, 'issues' => $issues];
    }

    private function analyzeStructure(DOMXPath $xpath, DOMDocument $dom, string $html): array
    {
        $issues = [];
        $data = [];

        // H1 Check
        $h1s = $xpath->query('//h1');
        $data['h1_count'] = $h1s->length;

        if ($h1s->length === 0) {
            $issues[] = ['type' => 'critical', 'message' => 'Missing H1 Heading'];
        } elseif ($h1s->length > 1) {
            $issues[] = ['type' => 'warning', 'message' => 'Multiple H1 Headings found (' . $h1s->length . ')'];
        }

        // Images Alt
        $images = $dom->getElementsByTagName('img');
        $missingAlt = 0;
        foreach ($images as $img) {
            if (!$img->hasAttribute('alt') || trim($img->getAttribute('alt')) === '') {
                $missingAlt++;
            }
        }
        $data['images_count'] = $images->length;
        $data['missing_alt'] = $missingAlt;

        if ($missingAlt > 0) {
            $issues[] = ['type' => 'warning', 'message' => "$missingAlt images are missing Alt attributes"];
        }

        return ['data' => $data, 'issues' => $issues];
    }

    private function analyzeTechnical($response, $loadTime): array
    {
        $issues = [];
        $data = ['load_time_ms' => $loadTime];

        if ($loadTime > 1000) {
            $issues[] = ['type' => 'warning', 'message' => "Slow Load Time: {$loadTime}ms (Recommend < 1000ms)"];
        }

        return ['data' => $data, 'issues' => $issues];
    }

    private function calculateScore(array $issues): int
    {
        $score = 100;
        foreach ($issues as $issue) {
            if ($issue['type'] === 'critical')
                $score -= 20;
            if ($issue['type'] === 'warning')
                $score -= 10;
        }
        return max(0, $score);
    }

    private function failedResult($url, $error): array
    {
        return [
            'success' => false,
            'url' => $url,
            'error' => $error,
            'scores' => ['overall' => 0]
        ];
    }
}
