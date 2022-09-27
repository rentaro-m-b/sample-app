<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\ReplyController;
use App\Models\Reply;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

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
    // Route::get('/create', 'create')->name('.create');
    // Route::post('/create', 'store')->name('.store');
    // Route::get('/{tweet}', 'show')->name('.show');
    // Route::get('/{tweet}/edit', 'edit')->name('.edit');
    Route::put('/', 'update')->name('.update');
    Route::delete('/', 'destroy')->name('.destroy');
    // user_idとtweet_idを引数とすると機能する
    Route::post('/bookmarks', 'store_bookmark')->name('.store_bookmark');
    Route::delete('/bookmarks', 'destroy_bookmark')->name('.destroy_bookmark');
    Route::post('/like', 'add_like')->name('.add_like');
    Route::delete('/like', 'delete_like')->name('.delete_like');
    Route::get('/search', 'search')->name('.search');
});
Route::controller(ReplyController::class)->prefix('replies')->name('replies')->group(function() {
    Route::get('/{tweet}/create', 'create')->name('.create');
    Route::post('/{tweet}/create', 'store')->name('.store');
    Route::delete('/{reply}', 'destroy')->name('.destroy');
});