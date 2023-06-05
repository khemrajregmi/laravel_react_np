<?php
namespace App\Services\ArticleFetchers;

use App\Models\Category;
use App\Repositories\ArticleRepository;
use GuzzleHttp\Client;

class NYTimesArticleFetcher
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
        $response = $client->get($this->apiUrl . $category->name . '.json', [
            'query' => [
                'api-key' => $this->apiKey,
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
}
