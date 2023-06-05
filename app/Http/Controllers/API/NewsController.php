<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Preference;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function newsFeed(Request $request): JsonResponse
    {
//        dd('la ya pugo');
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

    public function index(Request $request): JsonResponse
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

    public function userPreferenceNewsFeed(Request $request,$id): JsonResponse
    {
//        dd($request);
//
//        $perPage = $request->input('per_page', 10);
//        $page = $request->input('page', 1);
//
//        $query = Article::with('category')->where('category_id', $request->category)
//        ->orderBy('created_at', 'desc');

        // Apply filters
//        if ($preference->has('date')) {
//            $query->whereDate('created_at', $request->date);
//        }

//
//        if (!empty($preference->source)) {
//            $query->where('source', $request->source);
//        }
//
//        if (!empty($preference->athor)) {
//            $query->where('author', $request->author);
//        }
////
//        $posts = $query->paginate($perPage, ['*'], 'page', $page);

//        return response()->json([
//            'data' => $posts->items(),
//            'totalPages' => $posts->lastPage(),
//        ]);


        try {
            $perPage = $request->input('per_page', 10);
            $page = $request->input('page', 1);
            // Fetch preference data for the given user
            $preference = Preference::with('category')->where('user_id', $id)->first();
//            dd($preference->category_id);
            $query = Article::with('category')->where('category_id', $preference->category_id)
                ->orderBy('created_at', 'desc');            //            dd('la ya pugo');
            if (!empty($preference->source)) {
                $query->where('source', $request->source);
            }

            if (!empty($preference->athor)) {
                $query->where('author', $request->author);
            }
    //
            $posts = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'data' => $posts->items(),
                'totalPages' => $posts->lastPage(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
