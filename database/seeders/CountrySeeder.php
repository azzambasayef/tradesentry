<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            [
                'name' => 'Germany',
                'code' => 'DE',
                'code_alpha3' => 'DEU',
                'capital' => 'Berlin',
                'region' => 'Europe',
                'subregion' => 'Western Europe',
                'population' => 83240525,
                'area' => 357114.0,
                'currency_code' => 'EUR',
                'currency_name' => 'Euro',
                'lat' => 51.165691,
                'lng' => 10.451526,
            ],
            [
                'name' => 'China',
                'code' => 'CN',
                'code_alpha3' => 'CHN',
                'capital' => 'Beijing',
                'region' => 'Asia',
                'subregion' => 'Eastern Asia',
                'population' => 1402112000,
                'area' => 9706961.0,
                'currency_code' => 'CNY',
                'currency_name' => 'Chinese yuan',
                'lat' => 35.86166,
                'lng' => 104.195397,
            ],
            [
                'name' => 'Indonesia',
                'code' => 'ID',
                'code_alpha3' => 'IDN',
                'capital' => 'Jakarta',
                'region' => 'Asia',
                'subregion' => 'South-Eastern Asia',
                'population' => 273523621,
                'area' => 1904569.0,
                'currency_code' => 'IDR',
                'currency_name' => 'Indonesian rupiah',
                'lat' => -0.789275,
                'lng' => 113.921327,
            ],
            [
                'name' => 'Australia',
                'code' => 'AU',
                'code_alpha3' => 'AUS',
                'capital' => 'Canberra',
                'region' => 'Oceania',
                'subregion' => 'Australia and New Zealand',
                'population' => 25687041,
                'area' => 7692024.0,
                'currency_code' => 'AUD',
                'currency_name' => 'Australian dollar',
                'lat' => -25.274398,
                'lng' => 133.775136,
            ],
            [
                'name' => 'United States',
                'code' => 'US',
                'code_alpha3' => 'USA',
                'capital' => 'Washington, D.C.',
                'region' => 'Americas',
                'subregion' => 'Northern America',
                'population' => 329484123,
                'area' => 9372610.0,
                'currency_code' => 'USD',
                'currency_name' => 'United States dollar',
                'lat' => 37.09024,
                'lng' => -95.712891,
            ],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(['code' => $country['code']], $country);
        }
    }
}