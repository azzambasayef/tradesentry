<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Country;
use Illuminate\Support\Facades\Log;

class FetchPopulationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:population';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch population data for all countries from World Bank API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching population data from World Bank API...');
        
        try {
            // Fetch for all countries, using a recent year (e.g., 2022) where most data is complete
            $url = "https://api.worldbank.org/v2/country/all/indicator/SP.POP.TOTL?format=json&per_page=300&date=2022";
            $response = Http::timeout(30)->get($url);
            
            if ($response->successful() && isset($response[1])) {
                $data = $response[1];
                $count = 0;
                
                $this->output->progressStart(count($data));
                
                foreach ($data as $item) {
                    $alpha3 = $item['countryiso3code'] ?? null;
                    $population = $item['value'] ?? null;
                    
                    if ($alpha3 && $population !== null) {
                        $updated = Country::where('code_alpha3', $alpha3)->update(['population' => $population]);
                        if ($updated) {
                            $count++;
                        }
                    }
                    
                    $this->output->progressAdvance();
                }
                
                $this->output->progressFinish();
                $this->info("Successfully updated population for {$count} countries!");
            } else {
                $this->error('Failed to fetch data from World Bank API.');
            }
        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            Log::error('FetchPopulationCommand Error: ' . $e->getMessage());
        }
    }
}
