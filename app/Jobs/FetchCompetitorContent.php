<?php

namespace App\Jobs;

use App\Models\Competitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchCompetitorContent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Competitor $competitor) {}

    public function handle(): void
    {
        // Debugging line to see if it even starts
        $this->competitor->status = 'fetching';
        $this->competitor->save();

        $content = @file_get_contents($this->competitor->website_url);

        if ($content) {
            $this->competitor->raw_content = substr(strip_tags($content), 0, 10000);
            $this->competitor->status = 'fetching_completed';
        } else {
            $this->competitor->status = 'failed';
        }
        
        $this->competitor->save();
    }
}