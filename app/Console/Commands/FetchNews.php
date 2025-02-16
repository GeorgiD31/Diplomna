<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Article;

class FetchNews extends Command
{
    protected $signature = 'fetch:news';
    protected $description = 'Fetch news from the News API and save them into the database';

    public function handle()
    {
        $apiKey = env('NEWS_API_KEY');
        $url = 'https://newsapi.org/v2/top-headlines';

        $response = Http::get($url, [
            'apiKey' => $apiKey,
            'country' => 'us', //za sega kato placeholde posle shte dobavq i drugi
        ]);

        if ($response->successful()) {
            $articles = $response->json()['articles'];

            foreach ($articles as $news) {
                Article::updateOrCreate(
                    ['title' => $news['title']],
                    [
                        'author' => $news['author'],
                        'description' => $news['description'],
                        'content' => $news['content'],
                        'url' => $news['url'],
                        'url_to_image' => $news['urlToImage'],
                        'source_name' => $news['source']['name'] ?? 'Unknown',
                        'published_at' => $news['publishedAt'],
                        'user_id' => null,
                    ]
                );
            }

            $this->info('News fetched and saved successfully!');
        } else {
            $this->error('Failed to fetch news: ' . $response->body());
        }
    }
}