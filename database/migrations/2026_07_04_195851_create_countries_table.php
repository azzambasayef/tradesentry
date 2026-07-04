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
        Schema::create('countries', function (Blueprint $table) {
                        $table->id();
            $table->string('name');
            $table->string('code', 2);
            $table->string('code_alpha3', 3)->nullable();
            $table->string('capital')->nullable();
            $table->string('region')->nullable();
            $table->string('subregion')->nullable();
            $table->bigInteger('population')->nullable();
            $table->float('area')->nullable();
            $table->string('flag_url')->nullable();
            $table->string('currency_code')->nullable();
            $table->string('currency_name')->nullable();
            $table->string('languages')->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
