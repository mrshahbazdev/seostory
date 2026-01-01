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
        $url = $this->competitor->website_url;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // SSL issues bypass karne ke liye

        $html = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($html && $httpCode == 200) {
            // Clean the HTML
            $cleanText = preg_replace('/<(script|style)\b[^>]*>(.*?)<\/\1>/is', '', $html);
            $cleanText = strip_tags($cleanText);
            $cleanText = preg_replace('/\s+/', ' ', $cleanText);
            $cleanText = trim($cleanText);

            if (strlen($cleanText) > 200) {
                $this->competitor->update([
                    'raw_content' => substr($cleanText, 0, 15000), // Max 15k chars for AI
                    'status' => 'fetching_completed'
                ]);
                \Log::info("Fetch Successful for: " . $url . " (Size: " . strlen($cleanText) . ")");
            } else {
                \Log::warning("Content too short after cleaning for: " . $url);
                $this->competitor->update(['status' => 'failed']);
            }
        } else {
            \Log::error("CURL Failed for $url. HTTP Code: $httpCode");
            $this->competitor->update(['status' => 'failed']);
        }
    }
}