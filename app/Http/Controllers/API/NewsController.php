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
//        $perPage = $request->input('per_page', 10);
//        $page = $request->input('page', 1);
//
//        $posts = Article::orderBy('created_at', 'desc')
//            ->paginate($perPage, ['*'], 'page', $page);
//
//        return response()->json([
//            'data' => $posts->items(),
//            'totalPages' => $posts->lastPage(),
//        ]);
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
}
