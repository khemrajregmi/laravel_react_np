<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class AuthorContorller extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $authors = Article::distinct()
            ->pluck('author');

        return response()->json(['data' => $authors]);
    }
}
