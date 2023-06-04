<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function newsFeed(Request $request): \Illuminate\Http\JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $query = Article::with('category')->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('source')) {
            $query->where('source', $request->source);
        }

        $posts = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $posts->items(),
            'totalPages' => $posts->lastPage(),
        ]);
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $sources = $request->get('sources', []);
        $categories = $request->get('categories', []);
        $authors = $request->get('authors', []);

        $query = NewsArticle::query();

        if (!empty($sources)) {
            $query->whereIn('source', $sources);
        }

        if (!empty($categories)) {
            $query->whereIn('category', $categories);
        }

        if (!empty($authors)) {
            $query->whereIn('author', $authors);
        }

        $newsFeed = $query->get();

        return response()->json(['data' => $newsFeed]);
    }
}
