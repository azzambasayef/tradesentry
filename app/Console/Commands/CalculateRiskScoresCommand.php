<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RiskEngineService;
use App\Models\Country;

class CalculateRiskScoresCommand extends Command
{
    protected $signature = 'risk:calculate';
    protected $description = 'Calculate risk scores for all monitored countries using RiskEngineService';

    public function handle(RiskEngineService $riskEngine)
    {
        $this->info('Starting Risk Engine Calculation...');
        
        $countries = Country::all();
        $this->output->progressStart(count($countries));
        
        foreach ($countries as $country) {
            $riskEngine->calculateRisk($country);
            $this->output->progressAdvance();
        }
        
        $this->output->progressFinish();
        $this->info('Successfully calculated risk scores for all countries!');
    }
}
