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
        $this->competitor->update(['status' => 'fetching']);

        try {
            // Simple HTTP fetch (Future mein hum yahan Puppeteer ya Scraping API use kar saktay hain)
            $response = Http::timeout(30)->get($this->competitor->website_url);

            if ($response->successful()) {
                $this->competitor->update([
                    'raw_content' => strip_tags($response->body()), // Clean text for AI
                    'status' => 'completed'
                ]);
            } else {
                $this->competitor->update(['status' => 'failed']);
            }
        } catch (\Exception $e) {
            $this->competitor->update(['status' => 'failed']);
        }
    }
}