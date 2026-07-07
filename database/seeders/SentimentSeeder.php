<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PositiveWord;
use App\Models\NegativeWord;

class SentimentSeeder extends Seeder
{
    public function run(): void
    {
        $positiveWords = ['growth', 'increase', 'profit', 'stable', 'improve', 'success', 'recovery', 'boom', 'positive', 'advantage'];
        $negativeWords = ['war', 'crisis', 'inflation', 'delay', 'disaster', 'conflict', 'decline', 'loss', 'negative', 'disruption'];

        foreach ($positiveWords as $word) {
            PositiveWord::firstOrCreate(['word' => $word]);
        }

        foreach ($negativeWords as $word) {
            NegativeWord::firstOrCreate(['word' => $word]);
        }
    }
}