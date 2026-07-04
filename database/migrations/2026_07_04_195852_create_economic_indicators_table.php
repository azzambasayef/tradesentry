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
        Schema::create('economic_indicators', function (Blueprint $table) {
                        $table->id();
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->enum('indicator_type', ['gdp', 'inflation', 'population', 'export', 'import']);
            $table->integer('year');
            $table->decimal('value', 20, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('economic_indicators');
    }
};
