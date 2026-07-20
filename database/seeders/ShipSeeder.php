<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ship;
use App\Models\Port;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class ShipSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Ship::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = Faker::create();
        $ports = Port::all();
        
        if($ports->count() < 2) return;

        $shipsToInsert = [];
        $shipCompanies = ['Maersk', 'Evergreen', 'MSC', 'CMA CGM', 'Hapag-Lloyd', 'ONE', 'COSCO', 'ZIM', 'Yang Ming'];
        $now = now();

        for ($i = 0; $i < 500; $i++) {
            $origin = $ports->random();
            $destination = $ports->random();
            
            // Ensure origin and destination are different
            while($origin->id === $destination->id) {
                $destination = $ports->random();
            }
            
            $company = $faker->randomElement($shipCompanies);
            $shipName = $company . ' ' . $faker->lastName;
            
            $shipsToInsert[] = [
                'name' => $shipName,
                'origin_port_id' => $origin->id,
                'destination_port_id' => $destination->id,
                'progress_percentage' => $faker->randomFloat(2, 5, 95), // Between 5% and 95% progress
                'speed_knots' => $faker->randomFloat(1, 15.0, 25.0),
                'status' => 'In Transit',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($shipsToInsert, 100) as $chunk) {
            Ship::insert($chunk);
        }
    }
}
