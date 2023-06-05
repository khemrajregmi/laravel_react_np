<?php
namespace App\Services\ArticleFetchers;

use App\Models\Category;
use App\Repositories\ArticleRepository;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;

class NewsAPIArticleFetcher
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
                'q' => $category->name,
                'apiKey' => $this->apiKey,
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
}
