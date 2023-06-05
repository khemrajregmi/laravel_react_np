<?php
namespace App\Services\ArticleFetchers;

use App\Models\Category;
use App\Repositories\ArticleRepository;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;

class TheGuardianArticleFetcher
{
    protected $articleRepository;
    protected $apiUrl;
    protected $apiKey;

    public function __construct(ArticleRepository $articleRepository, $apiUrl, $apiKey)
    {
        $this->articleRepository = $articleRepository;
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
    }

    public function fetchArticles(Category $category): void
    {
        $client = new Client();
        $response = $client->get($this->apiUrl, [
            'query' => [
                'section' => $category->name,
                'page-size' => 200,
                'api-key' => $this->apiKey,
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
                'url' => $articleData['webUrl'],
                'source' => 'TheGuardian',
            ]);
        }
    }
}
