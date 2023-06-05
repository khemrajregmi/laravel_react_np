<?php
namespace App\Console\Commands;

use App\Models\Category;
use App\Repositories\ArticleRepository;
use App\Services\ArticleFetchers\NYTimesArticleFetcher;
use App\Services\ArticleFetchers\NewsAPIArticleFetcher;
use App\Services\ArticleFetchers\TheGuardianArticleFetcher;
use Illuminate\Console\Command;

class FetchNewsAndArticles extends Command
{
    protected $signature = 'app:fetch-news-and-articles';

    protected $description = 'Fetches new news and articles from news APIs';

    protected $articleRepository;
    protected $apiDetails;

    public function __construct(ArticleRepository $articleRepository)
    {
        parent::__construct();
        $this->articleRepository = $articleRepository;
        $this->apiDetails = [
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
    }

    public function handle()
    {
        $categories = Category::all();

        foreach ($categories as $category) {
            foreach ($this->apiDetails as $apiDetail => $apiValue) {
                switch ($apiDetail) {
                    case 'NYTimes':
                        $fetcher = new NYTimesArticleFetcher($this->articleRepository, $apiValue['url'], $apiValue['api_key']);
                        $fetcher->fetchArticles($category);
                        break;

                    case 'NewsAPI':
                        $fetcher = new NewsAPIArticleFetcher($this->articleRepository, $apiValue['url'], $apiValue['api_key']);
                        $fetcher->fetchArticles($category);
                        break;

                    default:
                        $fetcher = new TheGuardianArticleFetcher($this->articleRepository, $apiValue['url'], $apiValue['api_key']);
                        $fetcher->fetchArticles($category);
                        break;
                }
            }
        }

        $this->info('New articles fetched and stored successfully!');
    }
}
