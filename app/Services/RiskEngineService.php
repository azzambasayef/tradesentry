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
    /**
     * Calculate and update the risk score for a specific country
     */
    public function calculateRisk(Country $country)
    {
        // 1. Get Weights from DB
        $weights = RiskWeight::all()->pluck('weight', 'category')->toArray();
        
        $weightWeather = ($weights['weather'] ?? 30) / 100;
        $weightInflation = ($weights['inflation'] ?? 15) / 100;
        $weightCurrency = ($weights['currency'] ?? 20) / 100;
        $weightNews = ($weights['news'] ?? 35) / 100;

        // 2. Calculate Weather Risk (0-100)
        // Dummy normalization for now based on latitude (just for prototyping if no real weather exists)
        // Ideally from weather_data table. If extreme temp > 35 or < -5, risk is high.
        // We will simulate it based on region for now since weather API is only on-demand per country.
        $weatherRisk = rand(10, 80); // Replace with actual weather data logic if stored

        // 3. Calculate Inflation Risk (0-100)
        // High inflation (>10%) = High Risk (100)
        $inflationRecord = EconomicIndicator::where('country_id', $country->id)->where('indicator_type', 'inflation')->first();
        $inflationRisk = 20; // default
        if ($inflationRecord) {
            $inflationValue = $inflationRecord->value;
            // Normalization: 0% inflation = 0 risk. 15% inflation = 100 risk.
            $inflationRisk = min(max(($inflationValue / 15) * 100, 0), 100);
        }

        // 4. Calculate Currency Risk (0-100)
        // If currency is highly devalued (just a mockup logic for now: using random fluctuation for demonstration)
        $currencyRisk = rand(20, 70); 
        $exchangeRate = ExchangeRate::where('target_currency', $country->currency_code)->first();
        if ($exchangeRate) {
            // A highly simplistic currency risk metric for demonstration
            $currencyRisk = min(max(abs(sin($exchangeRate->rate)) * 100, 10), 90);
        }

        // 5. Calculate News Risk (0-100) (Phase 7 placeholder)
        $newsRisk = rand(10, 95); 

        // 6. Calculate Total Weighted Score
        $totalScore = ($weatherRisk * $weightWeather) +
                      ($inflationRisk * $weightInflation) +
                      ($currencyRisk * $weightCurrency) +
                      ($newsRisk * $weightNews);
                      
        // 7. Determine Risk Level
        $level = 'low';
        if ($totalScore >= 75) {
            $level = 'critical';
        } elseif ($totalScore >= 50) {
            $level = 'high';
        } elseif ($totalScore >= 25) {
            $level = 'medium';
        }

        // 8. Save to DB
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

    /**
     * Run risk calculation for all monitored countries
     */
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
