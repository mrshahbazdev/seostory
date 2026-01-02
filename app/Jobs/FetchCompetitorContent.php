<?php

namespace App\Jobs;

use App\Models\Competitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class FetchCompetitorContent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Competitor $competitor) {}

    public function handle(): void
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ])->timeout(30)->get($this->competitor->website_url);

            if ($response->successful()) {
                $html = $response->body();
                $dom = new \DOMDocument();
                @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
                $xpath = new \DOMXPath($dom);

                // 1. Text Stats
                $plainText = strip_tags($html);
                $wordCount = str_word_count($plainText);
                $charCount = strlen($plainText);

                // 2. Meta Data
                $title = $dom->getElementsByTagName('title')->item(0)?->nodeValue ?? 'No Title';
                $description = "";
                foreach ($dom->getElementsByTagName('meta') as $meta) {
                    if ($meta->getAttribute('name') === 'description') {
                        $description = $meta->getAttribute('content');
                    }
                }

                // 3. Tags Extraction
                $h1 = $dom->getElementsByTagName('h1');
                $h2 = $dom->getElementsByTagName('h2');
                $h3 = $dom->getElementsByTagName('h3');
                $strong = $dom->getElementsByTagName('strong');
                $b = $dom->getElementsByTagName('b');
                
                // 4. Images & Alt Tags
                $images = $dom->getElementsByTagName('img');
                $missingAlt = 0;
                foreach ($images as $img) {
                    if (!$img->getAttribute('alt')) $missingAlt++;
                }

                // 5. Links Audit
                $links = $dom->getElementsByTagName('a');
                $brokenLinks = 0; // Baseline check, deep check requires separate requests

                // Structure Data for Database
                $seoData = [
                    'title' => $title,
                    'description' => $description,
                    'stats' => [
                        'words' => $wordCount,
                        'chars' => $charCount,
                    ],
                    'tags' => [
                        'h1_count' => $h1->length,
                        'h1_list' => $this->getTagsContent($h1),
                        'h2_count' => $h2->length,
                        'h3_count' => $h3->length,
                        'bold_count' => ($strong->length + $b->length),
                    ],
                    'images' => [
                        'total' => $images->length,
                        'missing_alt' => $missingAlt,
                    ],
                    'links_count' => $links->length,
                ];

                $this->competitor->update([
                    'raw_content' => substr($plainText, 0, 10000),
                    'metadata' => [
                        'seo_report' => $seoData
                    ],
                    'status' => 'fetching_completed'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error("Scraping Error: " . $e->getMessage());
            $this->competitor->update(['status' => 'failed']);
        }
    }

    private function getTagsContent($tags) {
        $content = [];
        foreach ($tags as $tag) { $content[] = trim($tag->nodeValue); }
        return array_slice($content, 0, 5); // Sirf pehle 5 H1s (agar hon)
    }
}