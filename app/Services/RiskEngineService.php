<?php

namespace App\Services;

use App\Models\Country;
use App\Models\RiskWeight;
use App\Models\RiskScore;
use App\Models\ExchangeRate;
use App\Models\EconomicIndicator;
use Illuminate\Support\Facades\Log;

class RiskEngineService
{
    
    public function calculateRisk(Country $country)
    {
        $weights = RiskWeight::all()->pluck('weight', 'category')->toArray();
        $weightWeather = $weights['weather'] ?? 0.30;
        $weightInflation = $weights['inflation'] ?? 0.15;
        $weightCurrency = $weights['currency'] ?? 0.20; 
        $weightNews = $weights['news'] ?? 0.35;
        $weatherRisk = rand(10, 80); 
        $inflationRecord = EconomicIndicator::where('country_id', $country->id)->where('indicator_type', 'inflation')->first();
        $inflationRisk = 20; // default
        if ($inflationRecord) {
            $inflationValue = $inflationRecord->value;
            $inflationRisk = min(max(($inflationValue / 15) * 100, 0), 100);
        }

        $currencyRisk = rand(20, 70); 
        $exchangeRate = ExchangeRate::where('target_currency', $country->currency_code)->first();
        if ($exchangeRate) {
            $currencyRisk = min(max(abs(sin($exchangeRate->rate)) * 100, 10), 90);
        }

        $newsRisk = 30; // default risk if no news found
        $newsArticles = \App\Models\NewsArticle::where('country_id', $country->id)->get();
        if ($newsArticles->count() > 0) {
            $averageScore = \App\Models\NewsSentiment::whereIn('news_article_id', $newsArticles->pluck('id'))->avg('score');
            if ($averageScore !== null) {
                $newsRisk = $averageScore;
            }
        } 

        $totalScore = ($weatherRisk * $weightWeather) +
                      ($inflationRisk * $weightInflation) +
                      ($currencyRisk * $weightCurrency) +
                      ($newsRisk * $weightNews);
                      
        $level = 'low';
        if ($totalScore >= 75) {
            $level = 'critical';
        } elseif ($totalScore >= 50) {
            $level = 'high';
        } elseif ($totalScore >= 25) {
            $level = 'medium';
        }

        $riskScore = RiskScore::updateOrCreate(
            ['country_id' => $country->id],
            [
                'weather_risk' => $weatherRisk,
                'inflation_risk' => $inflationRisk,
                'currency_risk' => $currencyRisk,
                'news_risk' => $newsRisk,
                'total_score' => $totalScore,
                'risk_level' => $level,
                'calculated_at' => now(),
            ]
        );

        return $riskScore;
    }

    public function calculateAll()
    {
        $countries = Country::all();
        $results = [];
        foreach ($countries as $country) {
            $results[] = $this->calculateRisk($country);
        }
        return $results;
    }
}
