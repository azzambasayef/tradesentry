<?php

$seedersDir = __DIR__ . '/database/seeders';

$countrySeederContent = <<<PHP
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        \$countries = [
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

        foreach (\$countries as \$country) {
            Country::updateOrCreate(['code' => \$country['code']], \$country);
        }
    }
}
PHP;

$riskWeightSeederContent = <<<PHP
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiskWeight;

class RiskWeightSeeder extends Seeder
{
    public function run(): void
    {
        \$weights = [
            ['category' => 'weather', 'weight' => 0.30, 'description' => 'Weather Risk = 30%'],
            ['category' => 'inflation', 'weight' => 0.20, 'description' => 'Inflation Risk = 20%'],
            ['category' => 'news', 'weight' => 0.40, 'description' => 'Political News Risk = 40%'],
            ['category' => 'currency', 'weight' => 0.10, 'description' => 'Currency Risk = 10%'],
        ];

        foreach (\$weights as \$weight) {
            RiskWeight::updateOrCreate(['category' => \$weight['category']], \$weight);
        }
    }
}
PHP;

$sentimentSeederContent = <<<PHP
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PositiveWord;
use App\Models\NegativeWord;

class SentimentSeeder extends Seeder
{
    public function run(): void
    {
        \$positiveWords = ['growth', 'increase', 'profit', 'stable', 'improve', 'success', 'recovery', 'boom', 'positive', 'advantage'];
        \$negativeWords = ['war', 'crisis', 'inflation', 'delay', 'disaster', 'conflict', 'decline', 'loss', 'negative', 'disruption'];

        foreach (\$positiveWords as \$word) {
            PositiveWord::firstOrCreate(['word' => \$word]);
        }

        foreach (\$negativeWords as \$word) {
            NegativeWord::firstOrCreate(['word' => \$word]);
        }
    }
}
PHP;

$userSeederContent = <<<PHP
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@tradesentry.com'],
            [
                'name' => 'Admin Azzam',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@tradesentry.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password123'),
                'role' => 'user',
            ]
        );
    }
}
PHP;

file_put_contents($seedersDir . '/CountrySeeder.php', $countrySeederContent);
file_put_contents($seedersDir . '/RiskWeightSeeder.php', $riskWeightSeederContent);
file_put_contents($seedersDir . '/SentimentSeeder.php', $sentimentSeederContent);
file_put_contents($seedersDir . '/UserSeeder.php', $userSeederContent);

echo "Seeders created successfully.";
