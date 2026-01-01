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
        // 1. Status update to 'fetching'
        $this->competitor->update(['status' => 'fetching']);

        try {
            // 2. Fetch with Real Browser Headers
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.5',
            ])->timeout(30)->get($this->competitor->website_url);

            if ($response->successful()) {
                // 3. Clean Content: Remove Scripts, Styles, and HTML tags
                $html = $response->body();
                $cleanText = preg_replace('/<(script|style)\b[^>]*>(.*?)<\/\1>/is', '', $html);
                $cleanText = strip_tags($cleanText);
                $cleanText = preg_replace('/\s+/', ' ', $cleanText); // Extra spaces khatam karne ke liye

                $this->competitor->update([
                    'raw_content' => trim($cleanText),
                    'status' => 'completed'
                ]);

                // 4. Trigger AI Phase (Jab hum Phase 4 shuru karenge tab ye line use hogi)
                // AnalyzeCompetitorAI::dispatch($this->competitor);

            } else {
                Log::error("Fetching failed for {$this->competitor->website_url}: Status " . $response->status());
                $this->competitor->update(['status' => 'failed']);
            }
        } catch (\Exception $e) {
            Log::error("Exception while fetching {$this->competitor->website_url}: " . $e->getMessage());
            $this->competitor->update(['status' => 'failed']);
        }
    }
}