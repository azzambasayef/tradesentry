<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Country;
use App\Models\EconomicIndicator;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::all();
        return view('countries.index', compact('countries'));
    }

    public function show($id)
    {
        $country = Country::findOrFail($id);

        // Fetch World Bank GDP Data
        $gdpData = null;
        if ($country->code_alpha3) {
            // Check cache
            $gdpRecord = EconomicIndicator::where('country_id', $country->id)->where('indicator_type', 'gdp')->first();
            
            if (!$gdpRecord) {
                try {
                    $url = "https://api.worldbank.org/v2/country/{$country->code_alpha3}/indicator/NY.GDP.MKTP.CD?format=json";
                    $response = Http::timeout(5)->get($url);
                    if ($response->successful() && isset($response[1][0]['value'])) {
                        $value = $response[1][0]['value'];
                        $year = $response[1][0]['date'];
                        $gdpRecord = EconomicIndicator::create([
                            'country_id' => $country->id,
                            'indicator_type' => 'gdp',
                            'year' => $year,
                            'value' => $value
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to fetch GDP for {$country->code_alpha3}: " . $e->getMessage());
                }
            }
            $gdpData = $gdpRecord;
        }

        // Fetch World Bank Inflation Data
        $inflationData = null;
        if ($country->code_alpha3) {
            $inflationRecord = EconomicIndicator::where('country_id', $country->id)->where('indicator_type', 'inflation')->first();
            if (!$inflationRecord) {
                try {
                    $url = "https://api.worldbank.org/v2/country/{$country->code_alpha3}/indicator/FP.CPI.TOTL.ZG?format=json";
                    $response = Http::timeout(5)->get($url);
                    if ($response->successful() && isset($response[1][0]['value'])) {
                        $value = $response[1][0]['value'];
                        $year = $response[1][0]['date'];
                        $inflationRecord = EconomicIndicator::create([
                            'country_id' => $country->id,
                            'indicator_type' => 'inflation',
                            'year' => $year,
                            'value' => $value
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to fetch Inflation for {$country->code_alpha3}");
                }
            }
            $inflationData = $inflationRecord;
        }

        // Fetch World Bank Population Data
        $populationData = null;
        if ($country->code_alpha3) {
            $populationRecord = EconomicIndicator::where('country_id', $country->id)->where('indicator_type', 'population')->first();
            if (!$populationRecord) {
                try {
                    $url = "https://api.worldbank.org/v2/country/{$country->code_alpha3}/indicator/SP.POP.TOTL?format=json";
                    $response = Http::timeout(5)->get($url);
                    if ($response->successful() && isset($response[1][0]['value'])) {
                        $value = $response[1][0]['value'];
                        $year = $response[1][0]['date'];
                        $populationRecord = EconomicIndicator::create([
                            'country_id' => $country->id,
                            'indicator_type' => 'population',
                            'year' => $year,
                            'value' => $value
                        ]);
                        // Update the country's population column as well for the dashboard!
                        $country->update(['population' => $value]);
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to fetch Population for {$country->code_alpha3}");
                }
            }
            $populationData = $populationRecord;
        }

        // Fetch Open-Meteo Weather Data (Live)
        $weather = null;
        if ($country->lat && $country->lng) {
            try {
                $url = "https://api.open-meteo.com/v1/forecast?latitude={$country->lat}&longitude={$country->lng}&current=temperature_2m,relative_humidity_2m,wind_speed_10m";
                $response = Http::timeout(5)->get($url);
                if ($response->successful()) {
                    $weather = $response->json('current');
                }
            } catch (\Exception $e) {
                Log::error("Failed to fetch Weather for {$country->name}");
            }
        }

        $country->refresh();

        return view('countries.show', compact('country', 'gdpData', 'inflationData', 'weather'));
    }
}
