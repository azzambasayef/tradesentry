<?php

$schemas = [
    'countries' => <<<PHP
            \$table->id();
            \$table->string('name');
            \$table->string('code', 2);
            \$table->string('code_alpha3', 3)->nullable();
            \$table->string('capital')->nullable();
            \$table->string('region')->nullable();
            \$table->string('subregion')->nullable();
            \$table->bigInteger('population')->nullable();
            \$table->float('area')->nullable();
            \$table->string('flag_url')->nullable();
            \$table->string('currency_code')->nullable();
            \$table->string('currency_name')->nullable();
            \$table->string('languages')->nullable();
            \$table->decimal('lat', 10, 8)->nullable();
            \$table->decimal('lng', 11, 8)->nullable();
            \$table->timestamps();
PHP,
    'economic_indicators' => <<<PHP
            \$table->id();
            \$table->foreignId('country_id')->constrained()->onDelete('cascade');
            \$table->enum('indicator_type', ['gdp', 'inflation', 'population', 'export', 'import']);
            \$table->integer('year');
            \$table->decimal('value', 20, 2)->nullable();
            \$table->timestamps();
PHP,
    'weather_data' => <<<PHP
            \$table->id();
            \$table->foreignId('country_id')->constrained()->onDelete('cascade');
            \$table->float('temperature')->nullable();
            \$table->float('humidity')->nullable();
            \$table->float('wind_speed')->nullable();
            \$table->float('precipitation')->nullable();
            \$table->string('weather_code')->nullable();
            \$table->string('description')->nullable();
            \$table->timestamp('fetched_at')->nullable();
            \$table->timestamps();
PHP,
    'exchange_rates' => <<<PHP
            \$table->id();
            \$table->string('base_currency', 3);
            \$table->string('target_currency', 3);
            \$table->decimal('rate', 15, 6);
            \$table->timestamp('fetched_at')->nullable();
            \$table->timestamps();
PHP,
    'currency_histories' => <<<PHP
            \$table->id();
            \$table->string('base_currency', 3);
            \$table->string('target_currency', 3);
            \$table->decimal('rate', 15, 6);
            \$table->date('date');
            \$table->timestamps();
PHP,
    'risk_scores' => <<<PHP
            \$table->id();
            \$table->foreignId('country_id')->constrained()->onDelete('cascade');
            \$table->float('weather_risk')->nullable();
            \$table->float('inflation_risk')->nullable();
            \$table->float('currency_risk')->nullable();
            \$table->float('news_risk')->nullable();
            \$table->float('total_score')->nullable();
            \$table->enum('risk_level', ['low', 'medium', 'high', 'critical'])->nullable();
            \$table->timestamp('calculated_at')->nullable();
            \$table->timestamps();
PHP,
    'risk_weights' => <<<PHP
            \$table->id();
            \$table->string('category');
            \$table->decimal('weight', 5, 2);
            \$table->string('description')->nullable();
            \$table->boolean('is_active')->default(true);
            \$table->timestamps();
PHP,
    'news_articles' => <<<PHP
            \$table->id();
            \$table->foreignId('country_id')->nullable()->constrained()->onDelete('cascade');
            \$table->string('title');
            \$table->text('description')->nullable();
            \$table->text('content')->nullable();
            \$table->string('source_name')->nullable();
            \$table->string('source_url')->nullable();
            \$table->string('image_url')->nullable();
            \$table->timestamp('published_at')->nullable();
            \$table->timestamps();
PHP,
    'positive_words' => <<<PHP
            \$table->id();
            \$table->string('word')->unique();
            \$table->timestamps();
PHP,
    'negative_words' => <<<PHP
            \$table->id();
            \$table->string('word')->unique();
            \$table->timestamps();
PHP,
    'news_sentiments' => <<<PHP
            \$table->id();
            \$table->foreignId('news_article_id')->constrained()->onDelete('cascade');
            \$table->integer('positive_count')->default(0);
            \$table->integer('negative_count')->default(0);
            \$table->integer('total_words')->default(0);
            \$table->enum('sentiment', ['positive', 'negative', 'neutral'])->nullable();
            \$table->decimal('score', 5, 2)->nullable();
            \$table->timestamps();
PHP,
    'ports' => <<<PHP
            \$table->id();
            \$table->string('name');
            \$table->foreignId('country_id')->nullable()->constrained()->onDelete('set null');
            \$table->string('country_name')->nullable();
            \$table->decimal('lat', 10, 8)->nullable();
            \$table->decimal('lng', 11, 8)->nullable();
            \$table->string('port_type')->nullable();
            \$table->string('port_size')->nullable();
            \$table->string('status')->nullable();
            \$table->timestamps();
PHP,
    'watchlists' => <<<PHP
            \$table->id();
            \$table->foreignId('user_id')->constrained()->onDelete('cascade');
            \$table->foreignId('country_id')->constrained()->onDelete('cascade');
            \$table->text('notes')->nullable();
            \$table->timestamps();
PHP,
    'country_comparisons' => <<<PHP
            \$table->id();
            \$table->foreignId('user_id')->constrained()->onDelete('cascade');
            \$table->foreignId('country_id_1')->constrained('countries')->onDelete('cascade');
            \$table->foreignId('country_id_2')->constrained('countries')->onDelete('cascade');
            \$table->timestamps();
PHP,
    'articles' => <<<PHP
            \$table->id();
            \$table->foreignId('user_id')->constrained()->onDelete('cascade');
            \$table->string('title');
            \$table->text('content');
            \$table->string('category')->nullable();
            \$table->boolean('is_published')->default(false);
            \$table->timestamps();
PHP,
    'api_caches' => <<<PHP
            \$table->id();
            \$table->string('api_name');
            \$table->string('endpoint');
            \$table->json('parameters')->nullable();
            \$table->longText('response_data');
            \$table->timestamp('expires_at')->nullable();
            \$table->timestamps();
PHP,
    'activity_logs' => <<<PHP
            \$table->id();
            \$table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            \$table->string('action');
            \$table->text('description')->nullable();
            \$table->string('ip_address')->nullable();
            \$table->timestamps();
PHP
];

$dir = __DIR__ . '/database/migrations';
$files = scandir($dir);

foreach ($files as $file) {
    if (strpos($file, '.php') === false) continue;
    
    foreach ($schemas as $table => $schema) {
        if (strpos($file, 'create_' . $table . '_table') !== false) {
            $path = $dir . '/' . $file;
            $content = file_get_contents($path);
            
            // Replace the contents inside Schema::create
            $pattern = "/(\\\$table->id\(\);).*?(\\\$table->timestamps\(\);)/s";
            $content = preg_replace($pattern, $schema, $content);
            
            file_put_contents($path, $content);
            echo "Updated migration for table: $table\n";
        }
    }
}
echo "Done updating schemas.\n";
