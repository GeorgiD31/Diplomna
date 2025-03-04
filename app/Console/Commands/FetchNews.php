<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Article;
use App\Models\Category;
use Carbon\Carbon;

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
                'country'  => 'us',
                'category' => $categoryName,
            ]);

            if ($response->successful()) {
                $articlesData = $response->json()['articles'] ?? [];

                \Log::info("API Response for {$categoryName}:", $articlesData);

                $category = Category::firstOrCreate(['name' => $categoryName]);

                foreach ($articlesData as $newsItem) {
                    if (empty($newsItem['title']) || empty($newsItem['description']) || empty($newsItem['content']) || empty($newsItem['url']) || empty($newsItem['urlToImage']) || empty($newsItem['source']['name']) || empty($newsItem['publishedAt'])) {
                        \Log::warning("Skipping article due to missing required fields:", $newsItem);
                        continue;
                    }

                    \Log::info("Content for article '{$newsItem['title']}':", ['content' => $newsItem['content']]);

                    $article = Article::updateOrCreate(
                        ['title' => $newsItem['title']], 
                        [
                            'author'       => $newsItem['author']       ?? 'Unknown',
                            'description'  => $newsItem['description'],
                            'content'      => $newsItem['content'],
                            'url'          => $newsItem['url'],
                            'url_to_image' => $newsItem['urlToImage'],
                            'source_name'  => $newsItem['source']['name'],
                            'published_at' => Carbon::parse($newsItem['publishedAt'])->format('Y-m-d H:i:s'),
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
