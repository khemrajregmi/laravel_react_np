<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class NewsFeedController extends Controller
{
    public function index()
    {
        $newsFeed = Article::all();

        return response()->json(['data' => $newsFeed]);
    }
}
