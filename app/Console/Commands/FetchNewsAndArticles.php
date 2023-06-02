<?php
namespace App\Console\Commands;

use App\Models\Category;
use App\Repositories\ArticleRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class FetchNewsAndArticles extends Command
{
    protected $signature = 'app:fetch-news-and-articles';

    protected $description = 'Fetches new news and articles from news APIs';

    protected $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        parent::__construct();
        $this->articleRepository = $articleRepository;
    }

    public function handle()
    {
        $categories = Category::all();
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

        foreach ($categories as $category) {
            foreach ($apiDetails as $apiDetail => $apiValue) {
                switch ($apiDetail) {
                    case 'NYTimes':
                        $this->fetchNYTimesArticles($category, $apiValue);
                        break;

                    case 'NewsAPI':
                        $this->fetchNewsAPIArticles($category, $apiValue);
                        break;

                    default:
                        $this->fetchDefaultArticles($category, $apiValue);
                        break;
                }
            }
        }

        $this->info('New articles fetched and stored successfully!');
    }

    protected function fetchNYTimesArticles($category, $apiValue): void
    {
        $client = new Client();
        $response = $client->get($apiValue['url'] . $category->name . '.json', [
            'query' => [
                'api-key' => $apiValue['api_key'],
            ],
        ]);
        $articles = json_decode($response->getBody(), true)['results'];

        foreach ($articles as $articleData) {
            $this->articleRepository->create([
                'title' => $articleData['title'],
                'content' => $articleData['abstract'],
                'author' => $articleData['byline'],
                'category_id' => $category->id,
                'published_at' => $articleData['published_date'],
                'url' => $articleData['url'],
                'source' => 'NYTimes',
            ]);
        }
    }

    protected function fetchNewsAPIArticles($category, $apiValue): void
    {
        $client = new Client();
        $response = $client->get($apiValue['url'], [
            'query' => [
                'q' => $category->name,
                'apiKey' => $apiValue['api_key'],
            ],
        ]);
        $articles = json_decode($response->getBody(), true)['articles'];

        foreach ($articles as $articleData) {
            $this->articleRepository->create([
                'title' => $articleData['title'] ?? '',
                'content' => $articleData['description'],
                'author' => $articleData['author'],
                'category_id' => $category->id,
                'published_at' => Carbon::parse($articleData['publishedAt'])->format('Y-m-d H:i:s'),
                'url' => $articleData['url'],
                'source' => 'NewsAPI',
            ]);
        }
    }

    protected function fetchDefaultArticles($category, $apiValue): void
    {
        $client = new Client();
        $response = $client->get($apiValue['url'], [
            'query' => [
                'section' => $category->name,
                'page-size' => 200,
                'api-key' => $apiValue['api_key'],
            ],
        ]);
        $articles = json_decode($response->getBody(), true)['response']['results'];

        foreach ($articles as $articleData) {
            $publishedAt = isset($articleData['webPublicationDate']) ? Carbon::parse($articleData['webPublicationDate'])->format('Y-m-d H:i:s') : '';

            $this->articleRepository->create([
                'title' => $articleData['webTitle'],
                'content' => '',
                'author' => '',
                'category_id' => $category->id,
                'published_at' => $publishedAt,
                'url' => Carbon::parse($articleData['webPublicationDate'])->format('Y-m-d H:i:s'),
                'source' => 'TheGuardian',
            ]);
        }
    }
}
