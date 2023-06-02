<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Category;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

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
     *
     * @throws GuzzleException
     */
    public function handle()
    {
        $category = Category::all();
        $apiDetails = [
            'NewsAPI' => [
                'api_key' => env('NEWSAPI_API_KEY'),
                'url' => env('NEWSAPI_URL'),
            ],
            'NYTimes' => [
                'api_key' => env('NYTIMES_API_KEY'),
                'url' => env('NYTIMES_URL'),
            ],
            'TheGuardian' => [
                'api_key' => env('THEGUARDIAN_API_KEY'),
                'url' => env('THEGUARDIAN_URL'),
            ],
        ];

        foreach ($category as $cat) {
            foreach ($apiDetails as $apiDetail => $apiValue) {
                $client = new Client();
                switch ($apiDetail) {
                    case 'NYTimes':
                        $response = $client->get($apiValue['url'] . $cat->name . '.json', [
                            'query' => [
                                'api-key' => $apiValue['api_key'],
                            ],
                        ]);
                        $articles = json_decode($response->getBody(), true)['results'];

                        foreach ($articles as $articleData) {
                            Article::create([
                                'title' => $articleData['title'],
                                'content' => $articleData['abstract'],
                                'author' => $articleData['byline'],
                                'category_id' => $cat->id,
                                'published_at' => $articleData['published_date'],
                                'url' => $articleData['url'],
                                'source' => $apiDetail,
                            ]);
                        }
                        break;

                    case 'NewsAPI':
                        $response = $client->get($apiValue['url'], [
                            'query' => [
                                'q' => $cat->name,
                                'apiKey' => $apiValue['api_key'],
                            ],
                        ]);
                        $articles = json_decode($response->getBody(), true)['articles'];

                        foreach ($articles as $articleData) {
                            Article::create([
                                'title' => $articleData['title'] ?: '',
                                'content' => $articleData['description'],
                                'author' => $articleData['author'],
                                'category_id' => $cat->id,
                                'published_at' => Carbon::parse($articleData['publishedAt'])->format('Y-m-d H:i:s'),
                                'url' => $articleData['url'],
                                'source' => $apiDetail,
                            ]);
                        }
                        break;

                    default:
                        $response = $client->get($apiValue['url'], [
                            'query' => [
                                'section' => $cat->name,
                                'page-size' => 200,
                                'api-key' => $apiValue['api_key'],
                            ],
                        ]);
                        $articles = json_decode($response->getBody(), true)['response']['results'];
                        foreach ($articles as $articleData) {
                            $publishedAt = isset($articleData['webPublicationDate']) ? Carbon::parse($articleData['webPublicationDate'])->format('Y-m-d H:i:s') : '';
                            Article::create([
                                'title' => $articleData['webTitle'],
                                'content' => '',
                                'author' => '',
                                'category_id' => $cat->id,
                                'published_at' => $publishedAt,
                                'url' => Carbon::parse($articleData['webPublicationDate'])->format('Y-m-d H:i:s'),
                                'source' => $apiDetail,
                            ]);
                        }
                        break;
                }
            }
        }

        $this->info('New articles fetched and stored successfully!');
    }
}
