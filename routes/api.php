<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\ReplyController;


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

Route::controller(UserController::class)->prefix('users')->name('users')->group(function() {
    Route::post('/block', 'add_block')->name('.add_block');
    Route::delete('/block', 'delete_block')->name('.delete_block');
    Route::post('/follow', 'add_follow')->name('.add_follow');
    Route::delete('/follow', 'delete_follow')->name('.delete_follow');
    Route::get('/search', 'search')->name('.search');
});

// 「->prefix('tweets')->name('tweets')」で下部に指定した「.???」の親ルート的なものになっている。
// {tweet}は変数であり、現在はtweet_idが入るようにviewではなっている。
Route::controller(TweetController::class)->prefix('tweets')->name('tweets')->group(function() {
    // tweetの取得（リプライも取得している）
    Route::get('/', 'list');
    Route::post('/', 'store');
    Route::put('/', 'update');
    Route::delete('/{tweet}', 'destroy');
    Route::post('/replies', 'store_reply');
    Route::delete('/replies/{reply}', 'destroy_reply');
    // user_idとtweet_idを引数とすると機能する
    Route::post('/bookmarks', 'store_bookmark');
    Route::delete('/bookmarks/{bookmark}', 'destroy_bookmark');
    Route::post('/likes', 'store_like');
    Route::delete('/likes/{like}', 'destroy_like');
    Route::get('/search', 'search');
});