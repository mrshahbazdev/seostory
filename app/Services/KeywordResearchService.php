<?php

namespace App\Services;

use App\Models\Keyword;

class KeywordResearchService
{
    /**
     * Analyze a keyword and return metrics.
     * Uses simulation logic if no API key is present.
     */
    public function analyze(string $term): array
    {
        $term = strtolower(trim($term));

        // 1. Check Cache
        $cached = Keyword::where('term', $term)->first();
        if ($cached) {
            return [
                'term' => $cached->term,
                'volume' => $cached->volume,
                'difficulty' => $cached->difficulty,
                'cpc' => $cached->cpc,
                'results' => $cached->results,
            ];
        }

        // 2. Generate Simulated Data
        $data = $this->simulateMetrics($term);

        // 3. Cache Result
        Keyword::create([
            'term' => $term,
            'volume' => $data['volume'],
            'difficulty' => $data['difficulty'],
            'cpc' => $data['cpc'],
            'results' => $data['results'],
        ]);

        return $data;
    }

    private function simulateMetrics(string $term): array
    {
        $hash = crc32($term);
        $wordCount = str_word_count($term);
        $length = strlen($term);

        // VOLUME Logic:
        // Short words usually have higher volume.
        // We use the hash to make it determinstic but random-looking.
        $baseVolume = ($hash % 10000) * 10;
        if ($wordCount == 1)
            $baseVolume *= 5; // Single words have high volume
        if ($wordCount > 4)
            $baseVolume /= 5; // Long tail has low volume
        $volume = abs($baseVolume);
        if ($volume < 10)
            $volume = 10;

        // DIFFICULTY Logic (KD):
        // Short keywords = Hard (High KD). Long tail = Easy (Low KD).
        // 1 word -> 70-100
        // 2 words -> 40-70
        // 3 words -> 20-50
        // 4+ words -> 0-30
        $baseKd = 100 - ($wordCount * 20);
        // Add some jitter
        $jitter = ($hash % 20) - 10;
        $difficulty = max(0, min(100, $baseKd + $jitter));

        // CPC Logic:
        // "buy", "best", "service" imply high CPC.
        $commercialIntent = false;
        $commercialWords = ['buy', 'price', 'service', 'hire', 'agency', 'software', 'tool', 'best'];
        foreach ($commercialWords as $word) {
            if (str_contains($term, $word)) {
                $commercialIntent = true;
                break;
            }
        }
        $cpc = $commercialIntent ? (abs($hash % 500) / 100) + 2 : (abs($hash % 100) / 100);

        // RELATED KEYWORDS Logic:
        // Generate variations
        $related = [];
        $suffixes = ['guide', 'tutorial', 'examples', 'best practices', 'tools', 'software', '2026', 'price', 'reviews'];

        // Pick 5 random suffixes based on hash
        for ($i = 0; $i < 5; $i++) {
            $suffix = $suffixes[($hash + $i) % count($suffixes)];
            $relatedTerm = "$term $suffix";
            $related[] = [
                'term' => $relatedTerm,
                'volume' => floor($volume * 0.2), // Related usually lower volume
                'difficulty' => max(0, $difficulty - 20), // And easier
            ];
        }

        return [
            'term' => $term,
            'volume' => $volume,
            'difficulty' => $difficulty,
            'cpc' => round($cpc, 2),
            'results' => $related
        ];
    }
}
