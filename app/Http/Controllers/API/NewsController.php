<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $date = $request->input('date');
        $category = $request->input('category');
        $source = $request->input('source');

        $apiKey = 'YOUR_API_KEY'; // Replace with your actual API key

        $client = new Client();

        $response = $client->get('https://api.newsapi.org/v2/everything', [
            'query' => [
                'q' => $keyword,
                'from' => $date,
                'category' => $category,
                'sources' => $source,
                'apiKey' => $apiKey,
            ],
        ]);

//        dd($response);
        $articles = json_decode($response->getBody(), true)['articles'];

        // Implement pagination or infinite scrolling logic here
        // Return the relevant articles as JSON response
        return response()->json($articles);
    }
}
