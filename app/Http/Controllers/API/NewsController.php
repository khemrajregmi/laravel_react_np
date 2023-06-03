<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function newsFeed(Request $request): \Illuminate\Http\JsonResponse
    {
//        dd('la ya pugyo');
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $posts = Article::orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $posts->items(),
            'totalPages' => $posts->lastPage(),
        ]);
    }
}
