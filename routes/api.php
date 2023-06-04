<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\NewsController;
use App\Http\Controllers\API\AuthorContorller;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\NewsFeedController;
use App\Http\Controllers\API\PreferenceController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/feed', [NewsController::class, 'newsFeed']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/personalized-news-feed', [NewsFeedController::class, 'index']);

    Route::get('/categories', [CategoryController::class, 'index']);
//    Route::get('/authors', [AuthorContorller::class, 'index']);
//    Route::get('/news-feed', [NewsFeedController::class, 'index']);
    Route::post('/preferences', [PreferenceController::class, 'store']);
    Route::get('/preferences', [PreferenceController::class, 'index']);
});
