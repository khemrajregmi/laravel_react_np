<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\NewsController;

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
//    Route::get('/news', [NewsController::class, 'search']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
//    Route::get('/feed', [AuthController::class, 'newsFeed']);
//    Route::get('/feed', [NewsController::class, 'newsFeed']);

});
