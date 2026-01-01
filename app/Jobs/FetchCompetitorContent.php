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
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_ENCODING, ""); // Compression handle karne ke liye

        $html = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($html && $httpCode == 200) {
            // 1. Pehle Scripts, Styles aur SVG ko mukammal nikaal dein
            $cleanText = preg_replace([
                '/<script\b[^>]*>(.*?)<\/script>/is',
                '/<style\b[^>]*>(.*?)<\/style>/is',
                '/<svg\b[^>]*>(.*?)<\/svg>/is',
                '/<noscript\b[^>]*>(.*?)<\/noscript>/is',
                '//is' // HTML Comments nikaalne ke liye
            ], '', $html);

            // 2. HTML Tags hatayen
            $cleanText = strip_tags($cleanText);

            // 3. HTML Entities (jaise &nbsp; या &amp;) ko normal text mein badlein
            $cleanText = html_entity_decode($cleanText);

            // 4. Extra spaces, tabs aur new lines ko single space mein badlein
            $cleanText = preg_replace('/\s+/', ' ', $cleanText);
            
            // 5. Final Trim
            $cleanText = trim($cleanText);

            // Agar cleaning ke baad kafi text bacha hai
            if (strlen($cleanText) > 150) {
                $this->competitor->update([
                    'raw_content' => substr($cleanText, 0, 15000), 
                    'status' => 'fetching_completed'
                ]);
                Log::info("Fetch & Clean Successful for: " . $url . " (Cleaned Size: " . strlen($cleanText) . ")");
            } else {
                Log::warning("Content too short after cleaning for: " . $url);
                $this->competitor->update(['status' => 'failed']);
            }
        } else {
            Log::error("CURL Failed for $url. HTTP Code: $httpCode");
            $this->competitor->update(['status' => 'failed']);
        }
    }
}