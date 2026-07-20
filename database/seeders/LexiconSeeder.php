<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LexiconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('positive_words')->truncate();
        DB::table('negative_words')->truncate();

        $positives = ['agreement', 'deal', 'growth', 'recovery', 'expansion', 'investment', 'partnership', 'stability', 'surplus', 'breakthrough', 'easing', 'boost', 'record', 'improvement', 'progress', 'cooperation', 'reopening', 'ratification', 'stimulus', 'resolution', 'truce', 'deregulation', 'boom', 'surge', 'milestone', 'alliance', 'facilitation', 'optimism', 'rebound'];
        
        $negatives = ['war', 'conflict', 'sanction', 'tariff', 'blockade', 'piracy', 'disruption', 'shortage', 'delay', 'crisis', 'embargo', 'attack', 'strike', 'protest', 'closure', 'recession', 'inflation', 'shutdown', 'tension', 'threat', 'hurricane', 'typhoon', 'storm', 'reroute', 'backlog', 'insolvency', 'default', 'restriction', 'ban', 'siege'];

        foreach ($positives as $word) {
            DB::table('positive_words')->insert([
                'word' => strtolower($word),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        foreach ($negatives as $word) {
            DB::table('negative_words')->insert([
                'word' => strtolower($word),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
