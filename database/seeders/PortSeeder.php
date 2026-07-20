<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Port;
use App\Models\Country;
use Illuminate\Support\Facades\DB;

class PortSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Port::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $countries = Country::all()->keyBy('name');
        
        $realPorts = [
            // ASIA
            ['name' => 'Shanghai', 'country' => 'China', 'lat' => 31.2222, 'lng' => 121.4581, 'size' => 'Large'],
            ['name' => 'Singapore', 'country' => 'Singapore', 'lat' => 1.264, 'lng' => 103.84, 'size' => 'Large'],
            ['name' => 'Shenzhen', 'country' => 'China', 'lat' => 22.5029, 'lng' => 113.89, 'size' => 'Large'],
            ['name' => 'Busan', 'country' => 'South Korea', 'lat' => 35.101, 'lng' => 129.035, 'size' => 'Large'],
            ['name' => 'Hong Kong', 'country' => 'China', 'lat' => 22.333, 'lng' => 114.12, 'size' => 'Large'],
            ['name' => 'Tokyo', 'country' => 'Japan', 'lat' => 35.62, 'lng' => 139.77, 'size' => 'Large'],
            ['name' => 'Yokohama', 'country' => 'Japan', 'lat' => 35.44, 'lng' => 139.63, 'size' => 'Medium'],
            ['name' => 'Port Klang', 'country' => 'Malaysia', 'lat' => 3.00, 'lng' => 101.40, 'size' => 'Large'],
            ['name' => 'Tanjung Pelepas', 'country' => 'Malaysia', 'lat' => 1.36, 'lng' => 103.54, 'size' => 'Large'],
            ['name' => 'Tanjung Priok', 'country' => 'Indonesia', 'lat' => -6.11, 'lng' => 106.88, 'size' => 'Large'],
            ['name' => 'Tanjung Perak', 'country' => 'Indonesia', 'lat' => -7.19, 'lng' => 112.73, 'size' => 'Medium'],
            ['name' => 'Belawan', 'country' => 'Indonesia', 'lat' => 3.78, 'lng' => 98.69, 'size' => 'Medium'],
            ['name' => 'Makassar', 'country' => 'Indonesia', 'lat' => -5.13, 'lng' => 119.41, 'size' => 'Medium'],
            ['name' => 'Jawaharlal Nehru', 'country' => 'India', 'lat' => 18.95, 'lng' => 72.95, 'size' => 'Large'],
            ['name' => 'Chennai', 'country' => 'India', 'lat' => 13.10, 'lng' => 80.29, 'size' => 'Medium'],
            ['name' => 'Jebel Ali', 'country' => 'United Arab Emirates', 'lat' => 24.98, 'lng' => 55.06, 'size' => 'Large'],
            ['name' => 'Colombo', 'country' => 'Sri Lanka', 'lat' => 6.94, 'lng' => 79.84, 'size' => 'Medium'],
            ['name' => 'Ho Chi Minh', 'country' => 'Vietnam', 'lat' => 10.76, 'lng' => 106.70, 'size' => 'Medium'],
            ['name' => 'Hai Phong', 'country' => 'Vietnam', 'lat' => 20.86, 'lng' => 106.68, 'size' => 'Medium'],
            ['name' => 'Laem Chabang', 'country' => 'Thailand', 'lat' => 13.08, 'lng' => 100.87, 'size' => 'Medium'],
            ['name' => 'Manila', 'country' => 'Philippines', 'lat' => 14.59, 'lng' => 120.97, 'size' => 'Medium'],
            // EUROPE
            ['name' => 'Rotterdam', 'country' => 'Netherlands', 'lat' => 51.88, 'lng' => 4.28, 'size' => 'Large'],
            ['name' => 'Antwerp', 'country' => 'Belgium', 'lat' => 51.27, 'lng' => 4.34, 'size' => 'Large'],
            ['name' => 'Hamburg', 'country' => 'Germany', 'lat' => 53.53, 'lng' => 9.96, 'size' => 'Large'],
            ['name' => 'Bremen', 'country' => 'Germany', 'lat' => 53.07, 'lng' => 8.80, 'size' => 'Medium'],
            ['name' => 'Algeciras', 'country' => 'Spain', 'lat' => 36.13, 'lng' => -5.43, 'size' => 'Large'],
            ['name' => 'Valencia', 'country' => 'Spain', 'lat' => 39.46, 'lng' => -0.32, 'size' => 'Medium'],
            ['name' => 'Barcelona', 'country' => 'Spain', 'lat' => 41.38, 'lng' => 2.17, 'size' => 'Medium'],
            ['name' => 'Felixstowe', 'country' => 'United Kingdom', 'lat' => 51.95, 'lng' => 1.31, 'size' => 'Large'],
            ['name' => 'Southampton', 'country' => 'United Kingdom', 'lat' => 50.90, 'lng' => -1.40, 'size' => 'Medium'],
            ['name' => 'Le Havre', 'country' => 'France', 'lat' => 49.48, 'lng' => 0.11, 'size' => 'Large'],
            ['name' => 'Marseille', 'country' => 'France', 'lat' => 43.29, 'lng' => 5.36, 'size' => 'Medium'],
            ['name' => 'Genoa', 'country' => 'Italy', 'lat' => 44.40, 'lng' => 8.92, 'size' => 'Medium'],
            ['name' => 'Naples', 'country' => 'Italy', 'lat' => 40.83, 'lng' => 14.26, 'size' => 'Medium'],
            ['name' => 'Piraeus', 'country' => 'Greece', 'lat' => 37.94, 'lng' => 23.64, 'size' => 'Large'],
            ['name' => 'Gothenburg', 'country' => 'Sweden', 'lat' => 57.70, 'lng' => 11.97, 'size' => 'Medium'],
            ['name' => 'Aarhus', 'country' => 'Denmark', 'lat' => 56.15, 'lng' => 10.21, 'size' => 'Medium'],
            // NORTH AMERICA
            ['name' => 'Los Angeles', 'country' => 'United States', 'lat' => 33.72, 'lng' => -118.26, 'size' => 'Large'],
            ['name' => 'Long Beach', 'country' => 'United States', 'lat' => 33.75, 'lng' => -118.21, 'size' => 'Large'],
            ['name' => 'New York', 'country' => 'United States', 'lat' => 40.67, 'lng' => -74.04, 'size' => 'Large'],
            ['name' => 'Houston', 'country' => 'United States', 'lat' => 29.74, 'lng' => -95.27, 'size' => 'Large'],
            ['name' => 'Savannah', 'country' => 'United States', 'lat' => 32.12, 'lng' => -81.14, 'size' => 'Large'],
            ['name' => 'Seattle', 'country' => 'United States', 'lat' => 47.60, 'lng' => -122.33, 'size' => 'Medium'],
            ['name' => 'Miami', 'country' => 'United States', 'lat' => 25.77, 'lng' => -80.19, 'size' => 'Medium'],
            ['name' => 'Vancouver', 'country' => 'Canada', 'lat' => 49.28, 'lng' => -123.11, 'size' => 'Large'],
            ['name' => 'Halifax', 'country' => 'Canada', 'lat' => 44.64, 'lng' => -63.57, 'size' => 'Medium'],
            ['name' => 'Veracruz', 'country' => 'Mexico', 'lat' => 19.19, 'lng' => -96.13, 'size' => 'Medium'],
            ['name' => 'Manzanillo', 'country' => 'Mexico', 'lat' => 19.05, 'lng' => -104.31, 'size' => 'Medium'],
            // SOUTH AMERICA
            ['name' => 'Santos', 'country' => 'Brazil', 'lat' => -23.96, 'lng' => -46.30, 'size' => 'Large'],
            ['name' => 'Rio de Janeiro', 'country' => 'Brazil', 'lat' => -22.90, 'lng' => -43.20, 'size' => 'Medium'],
            ['name' => 'Callao', 'country' => 'Peru', 'lat' => -12.05, 'lng' => -77.14, 'size' => 'Medium'],
            ['name' => 'San Antonio', 'country' => 'Chile', 'lat' => -33.58, 'lng' => -71.61, 'size' => 'Medium'],
            ['name' => 'Valparaiso', 'country' => 'Chile', 'lat' => -33.04, 'lng' => -71.62, 'size' => 'Medium'],
            ['name' => 'Buenos Aires', 'country' => 'Argentina', 'lat' => -34.58, 'lng' => -58.37, 'size' => 'Medium'],
            ['name' => 'Cartagena', 'country' => 'Colombia', 'lat' => 10.39, 'lng' => -75.48, 'size' => 'Medium'],
            ['name' => 'Guayaquil', 'country' => 'Ecuador', 'lat' => -2.18, 'lng' => -79.88, 'size' => 'Medium'],
            // AFRICA & MIDDLE EAST
            ['name' => 'Durban', 'country' => 'South Africa', 'lat' => -29.87, 'lng' => 31.02, 'size' => 'Large'],
            ['name' => 'Cape Town', 'country' => 'South Africa', 'lat' => -33.92, 'lng' => 18.42, 'size' => 'Medium'],
            ['name' => 'Port Said', 'country' => 'Egypt', 'lat' => 31.26, 'lng' => 32.31, 'size' => 'Large'],
            ['name' => 'Alexandria', 'country' => 'Egypt', 'lat' => 31.20, 'lng' => 29.91, 'size' => 'Medium'],
            ['name' => 'Tanger Med', 'country' => 'Morocco', 'lat' => 35.88, 'lng' => -5.50, 'size' => 'Large'],
            ['name' => 'Casablanca', 'country' => 'Morocco', 'lat' => 33.57, 'lng' => -7.58, 'size' => 'Medium'],
            ['name' => 'Mombasa', 'country' => 'Kenya', 'lat' => -4.05, 'lng' => 39.63, 'size' => 'Medium'],
            ['name' => 'Lagos', 'country' => 'Nigeria', 'lat' => 6.44, 'lng' => 3.36, 'size' => 'Medium'],
            ['name' => 'Tema', 'country' => 'Ghana', 'lat' => 5.66, 'lng' => 0.01, 'size' => 'Medium'],
            ['name' => 'Jeddah', 'country' => 'Saudi Arabia', 'lat' => 21.48, 'lng' => 39.19, 'size' => 'Large'],
            ['name' => 'Doha', 'country' => 'Qatar', 'lat' => 25.28, 'lng' => 51.53, 'size' => 'Medium'],
            // OCEANIA
            ['name' => 'Melbourne', 'country' => 'Australia', 'lat' => -37.81, 'lng' => 144.91, 'size' => 'Large'],
            ['name' => 'Sydney', 'country' => 'Australia', 'lat' => -33.96, 'lng' => 151.21, 'size' => 'Large'],
            ['name' => 'Brisbane', 'country' => 'Australia', 'lat' => -27.46, 'lng' => 153.02, 'size' => 'Medium'],
            ['name' => 'Fremantle', 'country' => 'Australia', 'lat' => -32.05, 'lng' => 115.74, 'size' => 'Medium'],
            ['name' => 'Auckland', 'country' => 'New Zealand', 'lat' => -36.84, 'lng' => 174.77, 'size' => 'Medium'],
            ['name' => 'Tauranga', 'country' => 'New Zealand', 'lat' => -37.68, 'lng' => 176.16, 'size' => 'Medium'],
        ];

        $now = now();
        $portsToInsert = [];

        foreach ($realPorts as $mp) {
            $country = $countries->get($mp['country']);
            $portsToInsert[] = [
                'name' => 'Port of ' . $mp['name'],
                'country_id' => $country ? $country->id : null,
                'country_name' => $mp['country'],
                'lat' => $mp['lat'],
                'lng' => $mp['lng'],
                'port_type' => 'Seaport',
                'port_size' => $mp['size'],
                'status' => 'Open',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        Port::insert($portsToInsert);
    }
}
