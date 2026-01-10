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

        // 2. Generate Data (Hybrid)
        $simulatedMetrics = $this->simulateMetrics($term);

        // Fetch REAL related keywords from Google
        $realSuggestions = $this->fetchRealSuggestions($term);

        // If Google fails (offline/rate limit), use fallback simulation
        if (empty($realSuggestions)) {
            // Fallback simulation logic (simple suffix appending)
            // We can just rely on the simulateMetrics doing nothing for results, 
            // OR manually add some if needed. For now, empty is fine or we re-add the suffix loop.
            // Let's re-add the suffix loop purely as fallback if $realSuggestions is empty.
            $suffixes = ['guide', 'tutorial', 'examples', 'best practices', 'tools', 'software', '2026', 'price', 'reviews'];
            $hash = crc32($term);
            for ($i = 0; $i < 5; $i++) {
                $suffix = $suffixes[($hash + $i) % count($suffixes)];
                $relatedTerm = "$term $suffix";
                $relatedSim = $this->simulateMetrics($relatedTerm);
                $realSuggestions[] = [
                    'term' => $relatedTerm,
                    'volume' => floor($simulatedMetrics['volume'] * 0.2),
                    'difficulty' => max(0, $simulatedMetrics['difficulty'] - 20),
                ];
            }
        }

        $data = [
            'term' => $term,
            'volume' => $simulatedMetrics['volume'],
            'difficulty' => $simulatedMetrics['difficulty'],
            'cpc' => $simulatedMetrics['cpc'],
            'results' => $realSuggestions
        ];
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

    /**
     * Fetch real suggestions from Google Autocomplete.
     */
    private function fetchRealSuggestions(string $term): array
    {
        try {
            // Google Autocomplete API (Free & Public)
            $response = \Illuminate\Support\Facades\Http::get('http://suggestqueries.google.com/complete/search', [
                'client' => 'firefox',
                'q' => $term,
            ]);

            if ($response->successful()) {
                $suggestions = $response->json()[1] ?? [];

                // Limit to 10 suggestions
                $suggestions = array_slice($suggestions, 0, 10);

                $results = [];
                foreach ($suggestions as $suggestion) {
                    // Calculate "Mock" metrics for this "Real" keyword
                    $simulated = $this->simulateMetrics($suggestion);
                    $results[] = [
                        'term' => $suggestion,
                        'volume' => floor($simulated['volume'] * 0.8), // Slightly lower volume for long-tail
                        'difficulty' => max(0, $simulated['difficulty'] - 10),
                    ];
                }

                return $results;
            }
        } catch (\Exception $e) {
            // Fallback to pure simulation if API fails
            return [];
        }

        return [];
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
        // If Google API fails (or we are calling this recursively), returning empty related to prevent infinite loops
        // The FETCH method handles the related list now.

        return [
            'term' => $term,
            'volume' => $volume,
            'difficulty' => $difficulty,
            'cpc' => round($cpc, 2),
            'results' => [] // Results populated by parent caller
        ];
    }
}
