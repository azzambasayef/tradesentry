<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiskWeight;

class RiskWeightSeeder extends Seeder
{
    public function run(): void
    {
        $weights = [
            ['category' => 'weather', 'weight' => 0.30, 'description' => 'Weather Risk = 30%'],
            ['category' => 'inflation', 'weight' => 0.20, 'description' => 'Inflation Risk = 20%'],
            ['category' => 'news', 'weight' => 0.40, 'description' => 'Political News Risk = 40%'],
            ['category' => 'currency', 'weight' => 0.10, 'description' => 'Currency Risk = 10%'],
        ];

        foreach ($weights as $weight) {
            RiskWeight::updateOrCreate(['category' => $weight['category']], $weight);
        }
    }
}