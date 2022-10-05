<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(UserController::class)->prefix('user')->name('user')->group(function() {
    Route::post('/block', 'add_block');
    Route::delete('/block/{block}', 'delete_block');
    Route::post('/follow', 'add_follow');
    Route::delete('/follow/{follow}', 'delete_follow');
    Route::get('/search', 'search');
});

Route::controller(TweetController::class)->prefix('tweet')->name('tweet')->group(function() {
    Route::get('/', 'list');
    Route::post('/', 'store');
    Route::put('/{tweet}', 'update');
    Route::delete('/{tweet}', 'destroy');
    Route::post('/reply', 'store_reply');
    Route::delete('/reply/{reply}', 'destroy_reply');
    Route::post('/bookmark', 'store_bookmark');
    Route::delete('/bookmark/{favorites}', 'destroy_favorites');
    Route::post('/like', 'store_like');
    Route::delete('/like/{like}', 'destroy_like');
    Route::get('/search', 'search');
});