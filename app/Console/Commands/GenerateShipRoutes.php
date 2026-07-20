<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateShipRoutes extends Command
{
    protected $signature = 'route:generate';

    protected $description = 'Generate maritime searoute geometry using Node Geoprocessing Engine';

    public function handle()
    {
        $this->info('Starting Maritime Geoprocessing Engine...');
        $ships = \App\Models\Ship::with(['originPort', 'destinationPort'])->get();
        
        $input = [];
        foreach($ships as $ship) {
            if($ship->originPort && $ship->destinationPort) {
                $input[] = [
                    'id' => $ship->id,
                    'o_lat' => $ship->originPort->lat,
                    'o_lng' => $ship->originPort->lng,
                    'd_lat' => $ship->destinationPort->lat,
                    'd_lng' => $ship->destinationPort->lng,
                ];
            }
        }
        
        $inputFile = storage_path('app/routes_input.json');
        $outputFile = storage_path('app/routes_output.json');
        
        file_put_contents($inputFile, json_encode($input));
        
        $this->info('Executing Node.js searoute-js graph algorithm...');
        $nodePath = 'node'; // Assume node is in PATH
        $scriptPath = base_path('process_routes.cjs');
        
        $output = shell_exec("$nodePath \"$scriptPath\" \"$inputFile\" \"$outputFile\" 2>&1");
        $this->info($output);
        
        if (file_exists($outputFile)) {
            $results = json_decode(file_get_contents($outputFile), true);
            $bar = $this->output->createProgressBar(count($results));
            $bar->start();
            
            foreach($results as $res) {
                if ($res['geometry']) {
                    \App\Models\Ship::where('id', $res['id'])->update([
                        'route_geometry' => json_encode($res['geometry'])
                    ]);
                }
                $bar->advance();
            }
            $bar->finish();
            $this->newLine();
            $this->info('Successfully updated all ship route geometries!');
        } else {
            $this->error('Failed to generate routes. Output file not found.');
        }
    }
}
