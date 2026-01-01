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
            
            // Sirf kaam ka text bhej rahe hain
            $content = substr($this->competitor->raw_content, 0, 8000);

            $prompt = "Analyze this website content and give me: 
                       1. Main Value Proposition 
                       2. Target Audience 
                       3. Marketing Strengths.
                       Text: " . $content;

            $result = $client->geminiPro()->generateContent($prompt);

            $this->competitor->update([
                'metadata' => ['analysis' => $result->text()],
                'status' => 'completed'
            ]);
        } catch (\Exception $e) {
            \Log::error("Gemini Error: " . $e->getMessage());
            $this->competitor->update(['status' => 'failed']);
        }
    }
}