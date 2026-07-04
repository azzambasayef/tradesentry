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
        Schema::create('news_sentiments', function (Blueprint $table) {
                        $table->id();
            $table->foreignId('news_article_id')->constrained()->onDelete('cascade');
            $table->integer('positive_count')->default(0);
            $table->integer('negative_count')->default(0);
            $table->integer('total_words')->default(0);
            $table->enum('sentiment', ['positive', 'negative', 'neutral'])->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_sentiments');
    }
};
