<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Article;
use App\Models\Category;

class FetchNews extends Command
{
    
    protected $signature = 'fetch:news';

    protected $description = 'Fetch news from the News API for multiple categories and store them in the database';

    public function handle()
    {
        $apiKey = env('NEWS_API_KEY'); 
        $baseUrl = 'https://newsapi.org/v2/top-headlines';

     
        $categories = [
            'business',
            'entertainment',
            'general',
            'health',
            'science',
            'sports',
            'technology',
        ];

        foreach ($categories as $categoryName) {
            $this->info("Fetching {$categoryName} news...");

            $response = Http::get($baseUrl, [
                'apiKey'   => $apiKey,
                'country'  => 'us',       // za sega kato placeholde posle shte dobavq i drugi
                'category' => $categoryName,
            ]);

            if ($response->successful()) {
                $articlesData = $response->json()['articles'] ?? [];

    
                $category = Category::firstOrCreate(['name' => $categoryName]);

                foreach ($articlesData as $newsItem) {
                    $article = Article::updateOrCreate(
                        ['title' => $newsItem['title']], 
                        [
                            'author'       => $newsItem['author']       ?? 'Unknown',
                            'description'  => $newsItem['description']  ?? '',
                            'content'      => $newsItem['content']      ?? '',
                            'url'          => $newsItem['url']          ?? '',
                            'url_to_image' => $newsItem['urlToImage']    ?? '',
                            'source_name'  => $newsItem['source']['name'] ?? '',
                            'published_at' => $newsItem['publishedAt']   ?? now(),
                        ]
                    );

                    $article->categories()->syncWithoutDetaching([$category->id]);
                }

                $this->info("Fetched " . count($articlesData) . " articles for {$categoryName}.");
            } else {
                $this->error("Failed to fetch {$categoryName} news: " . $response->body());
            }
        }

        $this->info('News fetching completed!');
        return 0;
    }
}
