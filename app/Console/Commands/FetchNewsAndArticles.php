<?php

namespace App\Console\Commands;

use App\Models\Article;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class FetchNewsAndArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-news-and-articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches new news and articles from news APIs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Define the API keys for each news source
        $apiKeys = [
            'https://newsapi.org/v2/everything' => 'e144edd74e5a4181840711d7300f350e',
//            'source2' => 'API_KEY_2',
//            'source3' => 'API_KEY_3',
        ];

        $client = new Client();

        foreach ($apiKeys as $source => $apiKey) {
            // Fetch new articles using the news APIs for each source
            $response = $client->get($source, [
                'query' => [
                    'q' => 'tesla',
                    'apiKey' => $apiKey,
                ],
            ]);
            $articles = json_decode($response->getBody(), true)['articles'];
//            dd($articles);


            // Store the articles in the database
            foreach ($articles as $articleData) {
                $article = new Article();
                $article->content = $articleData['content'];
                $article->title = $articleData['title'];
                $article->author = $articleData['author'];
                $article->source = $articleData['source']['name'];
                $article->save();
            }
        }

        $this->info('New articles fetched and stored successfully!');
    }
}
