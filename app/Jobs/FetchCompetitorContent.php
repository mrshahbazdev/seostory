<?php

namespace App\Jobs;

use App\Models\Competitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB; // Direct DB access ke liye

class FetchCompetitorContent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Competitor $competitor) {}

    public function handle(): void
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36'
            ])->timeout(30)->get($this->competitor->website_url);

            if ($response->successful()) {
                $html = $response->body();
                $dom = new \DOMDocument();
                @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

                // 1. Text & Meta Stats
                $plainText = strip_tags($html);
                $wordCount = str_word_count($plainText);
                
                $title = $dom->getElementsByTagName('title')->item(0)?->nodeValue ?? 'No Title';
                $description = "";
                foreach ($dom->getElementsByTagName('meta') as $meta) {
                    if ($meta->getAttribute('name') === 'description') {
                        $description = $meta->getAttribute('content');
                    }
                }

                // 2. Headings & Tags
                $h1 = $dom->getElementsByTagName('h1');
                $h2 = $dom->getElementsByTagName('h2');
                $h3 = $dom->getElementsByTagName('h3');
                
                // 3. Images & Alt Audit
                $images = $dom->getElementsByTagName('img');
                $missingAlt = 0;
                foreach ($images as $img) {
                    if (!$img->getAttribute('alt')) $missingAlt++;
                }

                // Metadata Object Taiyar Karein
                $seoData = [
                    'seo_report' => [
                        'title' => trim($title),
                        'description' => trim($description),
                        'stats' => [
                            'words' => $wordCount,
                            'chars' => strlen($plainText),
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

                // 🚀 FORCE UPDATE: Direct SQL bypass model cache
                DB::table('competitors')
                    ->where('id', $this->competitor->id)
                    ->update([
                        'raw_content' => substr($plainText, 0, 15000),
                        'metadata' => json_encode($seoData), // Direct JSON string
                        'status' => 'fetching_completed',
                        'updated_at' => now()
                    ]);

                \Log::info("FORCE DB UPDATE SUCCESS for: " . $this->competitor->name);
            }
        } catch (\Exception $e) {
            \Log::error("Scraping Force Update Error: " . $e->getMessage());
            
            DB::table('competitors')
                ->where('id', $this->competitor->id)
                ->update(['status' => 'failed', 'updated_at' => now()]);
        }
    }
}