<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Country;
use Illuminate\Support\Facades\Log;

class FetchCountriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:countries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch countries data from REST Countries API and insert to database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching countries from REST Countries API...');
        
        try {
            // Menggunakan mirror GitHub dari dataset resmi REST Countries karena v3.1 down/deprecated
            $response = Http::timeout(60)->get('https://raw.githubusercontent.com/mledoze/countries/master/countries.json');
            
            if ($response->successful()) {
                $countries = $response->json();
                $count = 0;
                
                $this->output->progressStart(count($countries));
                
                foreach ($countries as $c) {
                    $code = $c['cca2'] ?? null;
                    if (!$code) {
                        $this->output->progressAdvance();
                        continue;
                    }
                    
                    $name = $c['name']['common'] ?? 'Unknown';
                    $code_alpha3 = $c['cca3'] ?? null;
                    $capital = isset($c['capital'][0]) ? $c['capital'][0] : null;
                    $region = $c['region'] ?? null;
                    $subregion = $c['subregion'] ?? null;
                    $population = $c['population'] ?? 0;
                    $area = $c['area'] ?? 0;
                    
                    // Currencies are dynamic keys
                    $currency_code = null;
                    $currency_name = null;
                    if (isset($c['currencies']) && is_array($c['currencies'])) {
                        $currKey = array_key_first($c['currencies']);
                        $currency_code = $currKey;
                        $currency_name = $c['currencies'][$currKey]['name'] ?? null;
                    }
                    
                    $lat = isset($c['latlng'][0]) ? $c['latlng'][0] : null;
                    $lng = isset($c['latlng'][1]) ? $c['latlng'][1] : null;
                    
                    Country::updateOrCreate(
                        ['code' => $code],
                        [
                            'name' => $name,
                            'code_alpha3' => $code_alpha3,
                            'capital' => $capital,
                            'region' => $region,
                            'subregion' => $subregion,
                            'population' => $population,
                            'area' => $area,
                            'currency_code' => $currency_code,
                            'currency_name' => $currency_name,
                            'lat' => $lat,
                            'lng' => $lng,
                        ]
                    );
                    
                    $count++;
                    $this->output->progressAdvance();
                }
                
                $this->output->progressFinish();
                $this->info("Successfully processed {$count} countries!");
            } else {
                $this->error('Failed to fetch data from API. Status: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            Log::error('FetchCountriesCommand Error: ' . $e->getMessage());
        }
    }
}
