<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Country;
use App\Models\NewsArticle;
use App\Services\SentimentAnalysisService;

class FetchNewsCommand extends Command
{
    protected $signature = 'fetch:news';
    protected $description = 'Fetch global news via RSS and run Lexicon Sentiment Analysis for supply chain risks';
    public function handle(SentimentAnalysisService $sentimentService)
    {
        $this->info('Fetching Global & Regional News from BBC RSS (Key-less API)...');
        
        $rssFeeds = [
            'Global' => 'http://feeds.bbci.co.uk/news/world/rss.xml',
            'Asia' => 'http://feeds.bbci.co.uk/news/world/asia/rss.xml',
            'Africa' => 'http://feeds.bbci.co.uk/news/world/africa/rss.xml',
            'Business' => 'http://feeds.bbci.co.uk/news/business/rss.xml'
        ];
        
        $countries = Country::all();
        $articlesCount = 0;
        
        // 1. Fetch from BBC Feeds (Broad Coverage)
        foreach ($rssFeeds as $region => $rssUrl) {
            $this->info("Scanning BBC $region feed...");
            try {
                $response = Http::timeout(10)->get($rssUrl);
                $xml = simplexml_load_string($response->body());
                if (!$xml) continue;
                
                foreach ($xml->channel->item as $item) {
                    $title = (string)$item->title;
                    $description = (string)$item->description;
                    $link = (string)$item->link;
                    $fullText = strtolower($title . ' ' . $description);
                    
                    foreach ($countries as $country) {
                        $countryName = strtolower($country->name);
                        if ($countryName == 'united states') $countryName = 'us';
                        if ($countryName == 'united kingdom') $countryName = 'uk';
                        
                        if (preg_match('/\b' . preg_quote($countryName, '/') . '\b/i', $fullText)) {
                            $exists = NewsArticle::where('source_url', $link)->where('country_id', $country->id)->exists();
                            if (!$exists) {
                                $sentimentService->processArticle($title, $description, $link, 'BBC ' . $region, $country->id);
                                $articlesCount++;
                            }
                        }
                    }
                }
            } catch (\Exception $e) {}
        }
        
        // 2. Fetch Targeted Google News RSS for countries with 0 news (or randomly) to ensure global coverage
        // Google News RSS is 100% free and requires NO API KEY!
        $this->info("Scanning Google News for specific empty countries...");
        
        // Pick top 20 countries that currently have NO news in the database to populate them
        $emptyCountries = Country::doesntHave('newsArticles')->inRandomOrder()->take(20)->get();
        
        // Let's also explicitly include some SEA/LatAm countries if they are empty
        $priorityCountries = Country::whereIn('name', ['Indonesia', 'Malaysia', 'Singapore', 'Brazil', 'Argentina', 'Vietnam', 'Thailand'])
                                     ->doesntHave('newsArticles')->get();
                                     
        $targetCountries = $priorityCountries->merge($emptyCountries)->take(25);
        
        foreach ($targetCountries as $country) {
            // URL Encode the query: "CountryName supply chain OR economy OR trade"
            $query = urlencode('"' . $country->name . '" (economy OR trade OR supply chain OR conflict)');
            $gNewsUrl = "https://news.google.com/rss/search?q={$query}&hl=en-US&gl=US&ceid=US:en";
            
            try {
                $response = Http::timeout(10)->get($gNewsUrl);
                $xml = simplexml_load_string($response->body());
                if (!$xml) continue;
                
                $itemCount = 0;
                foreach ($xml->channel->item as $item) {
                    if ($itemCount >= 3) break; // Max 3 articles per country to avoid blowing up DB
                    
                    $title = (string)$item->title;
                    
                    // Clean up Google News title (removes the " - Source" at the end)
                    $title = preg_replace('/ - [^-]+$/', '', $title);
                    
                    $description = strip_tags((string)$item->description);
                    $link = (string)$item->link;
                    
                    $exists = NewsArticle::where('source_url', $link)->where('country_id', $country->id)->exists();
                    if (!$exists) {
                        $sentimentService->processArticle($title, $description, $link, 'Google News', $country->id);
                        $articlesCount++;
                        $itemCount++;
                        $this->line("Stored targeted news for: [" . $country->name . "]");
                    }
                }
                // Sleep slightly to avoid being rate limited by Google
                usleep(500000); 
            } catch (\Exception $e) {}
        }
        
        $this->info("Successfully fetched and analyzed $articlesCount new articles. (Including targeted SEA/LatAm countries)");
    }
}
