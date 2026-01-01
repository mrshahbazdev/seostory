<?php

namespace App\Jobs;

use App\Models\Competitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchCompetitorContent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Competitor $competitor) {}

    public function handle(): void
    {
        $this->competitor->update(['status' => 'fetching']);

        try {
            // High-level Browser Headers
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.5',
                'Referer' => 'https://www.google.com/',
            ])
            ->withOptions(['allow_redirects' => true, 'verify' => false]) 
            ->timeout(30)
            ->get($this->competitor->website_url);

            if ($response->successful()) {
                $html = $response->body();
                
                // Script aur Style tags ko nikalna zaroori hai
                $cleanText = preg_replace('/<(script|style)\b[^>]*>(.*?)<\/\1>/is', '', $html);
                $cleanText = strip_tags($cleanText);
                // Extra lines aur spaces khatam karna
                $cleanText = preg_replace('/\s+/', ' ', $cleanText);

                if (strlen(trim($cleanText)) < 100) {
                    \Log::error("Content too short for: " . $this->competitor->website_url);
                    $this->competitor->update(['status' => 'failed']);
                    return;
                }

                $this->competitor->update([
                    'raw_content' => trim($cleanText),
                    'status' => 'fetching_completed'
                ]);
            } else {
                \Log::error("Fetch Failed. Status: " . $response->status());
                $this->competitor->update(['status' => 'failed']);
            }
        } catch (\Exception $e) {
            \Log::error("Fetch Exception: " . $e->getMessage());
            $this->competitor->update(['status' => 'failed']);
        }
    }
}