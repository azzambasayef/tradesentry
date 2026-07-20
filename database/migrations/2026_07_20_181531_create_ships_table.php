<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ships', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('origin_port_id')->constrained('ports')->onDelete('cascade');
            $table->foreignId('destination_port_id')->constrained('ports')->onDelete('cascade');
            $table->decimal('progress_percentage', 5, 2)->default(0.00);
            $table->decimal('speed_knots', 5, 2)->default(20.00); // e.g. 20 knots
            $table->string('status')->default('In Transit');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ships');
    }
};
