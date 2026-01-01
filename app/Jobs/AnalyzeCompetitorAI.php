<?php

namespace App\Jobs;

use App\Models\Competitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Gemini;

class AnalyzeCompetitorAI implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Competitor $competitor) {}

    public function handle(): void
    {
        $this->competitor->update(['status' => 'analyzing']);

        try {
            $client = Gemini::client(env('GEMINI_API_KEY'));
            
            $content = substr($this->competitor->raw_content, 0, 10000);

            $prompt = "Analyze this website content and provide: 
                    1. Core Value Proposition 
                    2. Main Services 
                    3. Marketing Strategy. 
                    Content: " . $content;

            // YAHAN CHANGE HAI: geminiPro() ki jagah gemini15Flash() use karein
            $result = $client->gemini15Flash()->generateContent($prompt);

            $this->competitor->update([
                'metadata' => ['analysis' => $result->text()],
                'status' => 'completed'
            ]);
            
            \Log::info("Gemini Analysis Success for: " . $this->competitor->name);

        } catch (\Exception $e) {
            \Log::error("Gemini Error: " . $e->getMessage());
            $this->competitor->update(['status' => 'failed']);
        }
    }
}