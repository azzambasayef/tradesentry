<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('risk_scores', function (Blueprint $table) {
                        $table->id();
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->float('weather_risk')->nullable();
            $table->float('inflation_risk')->nullable();
            $table->float('currency_risk')->nullable();
            $table->float('news_risk')->nullable();
            $table->float('total_score')->nullable();
            $table->enum('risk_level', ['low', 'medium', 'high', 'critical'])->nullable();
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_scores');
    }
};
