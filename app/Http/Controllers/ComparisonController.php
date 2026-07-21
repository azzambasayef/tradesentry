<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use Illuminate\Support\Facades\Http;

class ComparisonController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('name', 'asc')->get();
        return view('compare.index', compact('countries'));
    }

    public function compare(Request $request)
    {
        $request->validate([
            'country1_id' => 'required|exists:countries,id',
            'country2_id' => 'required|exists:countries,id',
        ]);

        $country1 = Country::with(['riskScore'])->find($request->country1_id);
        $country2 = Country::with(['riskScore'])->find($request->country2_id);

        $weather1 = $this->fetchWeather($country1);
        $weather2 = $this->fetchWeather($country2);

        $economy1 = [
            'gdp' => $this->fetchLatestIndicator($country1, 'gdp'),
            'inflation' => $this->fetchLatestIndicator($country1, 'inflation')
        ];
        
        $economy2 = [
            'gdp' => $this->fetchLatestIndicator($country2, 'gdp'),
            'inflation' => $this->fetchLatestIndicator($country2, 'inflation')
        ];

        return response()->json([
            'country1' => [
                'details' => $country1,
                'weather' => $weather1,
                'economy' => $economy1
            ],
            'country2' => [
                'details' => $country2,
                'weather' => $weather2,
                'economy' => $economy2
            ]
        ]);
    }

    private function fetchWeather($country)
    {
        if ($country->lat && $country->lng) {
            try {
                $url = "https://api.open-meteo.com/v1/forecast?latitude={$country->lat}&longitude={$country->lng}&current=temperature_2m,relative_humidity_2m,wind_speed_10m";
                $response = Http::timeout(5)->get($url);
                if ($response->successful()) {
                    return $response->json('current');
                }
            } catch (\Exception $e) {}
        }
        return null;
    }

    private function fetchLatestIndicator($country, $type)
    {
        $indicator = \App\Models\EconomicIndicator::where('country_id', $country->id)
            ->where('indicator_type', $type)
            ->orderBy('year', 'desc')
            ->first();

        if ($indicator && !is_null($indicator->value)) {
            return $indicator->value;
        }

        if (!$country->code_alpha3) return 'N/A';

        $indicatorCode = $type === 'gdp' ? 'NY.GDP.MKTP.CD' : 'FP.CPI.TOTL.ZG';
        try {
            $url = "https://api.worldbank.org/v2/country/{$country->code_alpha3}/indicator/{$indicatorCode}?format=json";
            $response = Http::timeout(5)->get($url);
            if ($response->successful() && isset($response[1]) && is_array($response[1])) {
                $recentYears = array_slice($response[1], 0, 4);
                foreach ($recentYears as $item) {
                    if (isset($item['value']) && !is_null($item['value'])) {
                        \App\Models\EconomicIndicator::create([
                            'country_id' => $country->id,
                            'indicator_type' => $type,
                            'year' => $item['date'],
                            'value' => $item['value']
                        ]);
                        return $item['value'];
                    }
                }
            }
        } catch (\Exception $e) {}

        return 'N/A';
    }
}
