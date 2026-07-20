<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\NewsArticle;
use App\Models\NewsSentiment;

class SentimentAnalysisService
{
    protected $positiveWords = [];
    protected $negativeWords = [];

    public function __construct()
    {
        // Cache the dictionaries in memory to avoid repetitive DB calls
        $this->positiveWords = DB::table('positive_words')->pluck('word')->toArray();
        $this->negativeWords = DB::table('negative_words')->pluck('word')->toArray();
    }

    /**
     * Store and analyze an article
     */
    public function processArticle($title, $description, $url, $source, $countryId, $publishedAt = null)
    {
        $contentToAnalyze = strtolower($title . " " . $description);
        
        $positiveCount = 0;
        $negativeCount = 0;

        // Use regex with word boundaries \b to ensure exact match and avoid substring matching (e.g. warfare matched by war)
        foreach ($this->positiveWords as $word) {
            $pattern = '/\b' . preg_quote($word, '/') . '\b/i';
            $positiveCount += preg_match_all($pattern, $contentToAnalyze);
        }

        foreach ($this->negativeWords as $word) {
            $pattern = '/\b' . preg_quote($word, '/') . '\b/i';
            $negativeCount += preg_match_all($pattern, $contentToAnalyze);
        }

        // Calculate a basic Lexicon Score
        $netScore = $negativeCount - $positiveCount;
        $normalizedRisk = 50 + ($netScore * 10);
        $riskScore = max(0, min(100, $normalizedRisk)); 

        $sentimentStr = 'neutral';
        if ($netScore > 0) $sentimentStr = 'negative';
        if ($netScore < 0) $sentimentStr = 'positive';

        // Save to DB
        $article = NewsArticle::create([
            'country_id' => $countryId,
            'title' => $title,
            'description' => $description,
            'source_url' => $url,
            'source_name' => $source,
            'published_at' => $publishedAt ?? now(),
        ]);

        NewsSentiment::create([
            'news_article_id' => $article->id,
            'positive_count' => $positiveCount,
            'negative_count' => $negativeCount,
            'total_words' => str_word_count($contentToAnalyze),
            'sentiment' => $sentimentStr,
            'score' => $riskScore
        ]);

        return $article;
    }
}
