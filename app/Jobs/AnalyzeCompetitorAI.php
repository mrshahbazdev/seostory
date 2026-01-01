<?php

namespace App\Jobs;

use App\Models\Competitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use OpenAI;

class AnalyzeCompetitorAI implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Competitor $competitor) {}

    public function handle(): void
    {
        $this->competitor->update(['status' => 'analyzing']);

        try {
            $client = OpenAI::client(env('OPENAI_API_KEY'));

            $content = substr($this->competitor->raw_content, 0, 8000);

            $prompt = "You are an expert marketing analyst. Analyze the following website content of a competitor:
                    1. What is their core Value Proposition?
                    2. List their Key Products or Services.
                    3. Identify their main Marketing Strategy.
                    
                    Website Content: " . $content;

            $response = $client->chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'You provide professional, concise marketing intelligence reports.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            $analysisResult = $response->choices[0]->message->content;

            $this->competitor->update([
                'metadata' => ['analysis' => $analysisResult],
                'status' => 'completed'
            ]);
            
            \Log::info("OpenAI Analysis Success for: " . $this->competitor->name);

        } catch (\Exception $e) {
            \Log::error("OpenAI Error: " . $e->getMessage());
            $this->competitor->update(['status' => 'failed']);
        }
    }
}