<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class FetchCompetitorContent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param mixed $model (Competitor ya CompetitorPage ka object)
     * @param bool $isSubPage (Batata hai ke ye internal page hai ya main domain)
     */
    public function __construct(public $model, public bool $isSubPage = false) {}

    public function handle(): void
    {
        // 1. URL Select karein model type ke mutabiq
        $url = $this->isSubPage ? $this->model->url : $this->model->website_url;
        $tableName = $this->isSubPage ? 'competitor_pages' : 'competitors';

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36'
            ])->timeout(30)->get($url);

            if ($response->successful()) {
                $html = $response->body();
                $dom = new \DOMDocument();
                @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

                // --- DEEP SEO EXTRACTION ---
                
                // Text Stats
                $plainText = strip_tags($html);
                $wordCount = str_word_count($plainText);
                
                // Tags Counting
                $paragraphs = $dom->getElementsByTagName('p');
                $h1 = $dom->getElementsByTagName('h1');
                $h2 = $dom->getElementsByTagName('h2');
                $h3 = $dom->getElementsByTagName('h3');
                $strong = $dom->getElementsByTagName('strong');
                $bold = $dom->getElementsByTagName('b');

                // Images & Alt Audit
                $images = $dom->getElementsByTagName('img');
                $missingAlt = 0;
                foreach ($images as $img) {
                    if (!$img->getAttribute('alt')) $missingAlt++;
                }

                // Metadata Structure
                $seoData = [
                    'seo_report' => [
                        'title' => trim($dom->getElementsByTagName('title')->item(0)?->nodeValue ?? 'No Title'),
                        'stats' => [
                            'words' => $wordCount,
                            'paragraphs' => $paragraphs->length,
                            'bold_elements' => ($strong->length + $bold->length),
                        ],
                        'tags' => [
                            'h1_count' => $h1->length,
                            'h2_count' => $h2->length,
                            'h3_count' => $h3->length,
                        ],
                        'images' => [
                            'total' => $images->length,
                            'missing_alt' => $missingAlt,
                        ]
                    ]
                ];

                // 🚀 FORCE DATABASE UPDATE (Direct SQL)
                DB::table($tableName)
                    ->where('id', $this->model->id)
                    ->update([
                        'raw_content' => substr($plainText, 0, 15000),
                        'metadata' => json_encode($seoData),
                        'status' => 'fetching_completed',
                        'updated_at' => now()
                    ]);

                \Log::info("Audit Success for [$tableName]: " . $url);
            }
        } catch (\Exception $e) {
            \Log::error("Scraping Force Update Error on [$url]: " . $e->getMessage());
            
            DB::table($tableName)
                ->where('id', $this->model->id)
                ->update(['status' => 'failed', 'updated_at' => now()]);
        }
    }
}