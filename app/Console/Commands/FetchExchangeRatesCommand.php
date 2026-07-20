<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\ExchangeRate;
use App\Models\CurrencyHistory;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FetchExchangeRatesCommand extends Command
{
    protected $signature = 'fetch:exchange-rates';
    protected $description = 'Fetch real-time exchange rates from ExchangeRate API and generate historical data for Chart.js';

    public function handle()
    {
        $this->info('Fetching exchange rates from ExchangeRate API...');
        
        try {
            $url = "https://open.er-api.com/v6/latest/USD";
            $response = Http::timeout(10)->get($url);
            
            if ($response->successful() && isset($response['rates'])) {
                $rates = $response['rates'];
                
                // Clear old current rates
                ExchangeRate::truncate();
                
                $count = 0;
                $this->output->progressStart(count($rates));
                
                foreach ($rates as $currency => $rate) {
                    ExchangeRate::create([
                        'base_currency' => 'USD',
                        'target_currency' => $currency,
                        'rate' => $rate,
                        'fetched_at' => now(),
                    ]);
                    
                    // SMART TRICK FOR PROTOTYPING: Generate 30 days of historical data based on current rate.
                    // This creates a realistic ±1% random fluctuation daily so Chart.js looks dynamic.
                    $exists = CurrencyHistory::where('target_currency', $currency)->exists();
                    if (!$exists) {
                        $histories = [];
                        $currentBaseRate = $rate;
                        for ($i = 30; $i >= 0; $i--) {
                            $date = Carbon::now()->subDays($i)->format('Y-m-d');
                            // Fluctuate up to 1%
                            $fluctuation = $currentBaseRate * (rand(-100, 100) / 10000); 
                            $historicalRate = $currentBaseRate + $fluctuation;
                            
                            $histories[] = [
                                'base_currency' => 'USD',
                                'target_currency' => $currency,
                                'rate' => $historicalRate,
                                'date' => $date,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                        }
                        CurrencyHistory::insert($histories);
                    }
                    
                    $count++;
                    $this->output->progressAdvance();
                }
                
                $this->output->progressFinish();
                $this->info("Successfully updated {$count} exchange rates and historical trends!");
            } else {
                $this->error('Failed to fetch data from ExchangeRate API.');
            }
        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            Log::error('FetchExchangeRatesCommand Error: ' . $e->getMessage());
        }
    }
}
