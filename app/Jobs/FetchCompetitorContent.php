<?php

namespace App\Jobs;

use App\Models\Competitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        // Nike jaisi sites ke liye headers bohat zaroori hain
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.5',
            'Upgrade-Insecure-Requests: 1',
            'Cache-Control: max-age=0',
        ]);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_ENCODING, ""); 

        $html = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($html && $httpCode == 200) {
            // SAFE CLEANING: Sirf Script aur Style hatayen, baki rehne den
            $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
            $html = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $html);
            
            $cleanText = strip_tags($html);
            $cleanText = html_entity_decode($cleanText);
            $cleanText = preg_replace('/\s+/', ' ', $cleanText);
            $cleanText = trim($cleanText);

            if (strlen($cleanText) > 100) {
                $this->competitor->update([
                    'raw_content' => substr($cleanText, 0, 15000), 
                    'status' => 'fetching_completed'
                ]);
                Log::info("Success: Data fetched for " . $url);
            } else {
                Log::warning("Cleaning Error: Content too short for " . $url);
                $this->competitor->update(['status' => 'failed']);
            }
        } else {
            Log::error("Fetch Failed for $url. Code: $httpCode. Error: $error");
            $this->competitor->update(['status' => 'failed']);
        }
    }
}