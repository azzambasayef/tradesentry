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
        Schema::create('weather_data', function (Blueprint $table) {
                        $table->id();
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->float('temperature')->nullable();
            $table->float('humidity')->nullable();
            $table->float('wind_speed')->nullable();
            $table->float('precipitation')->nullable();
            $table->string('weather_code')->nullable();
            $table->string('description')->nullable();
            $table->timestamp('fetched_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_data');
    }
};
