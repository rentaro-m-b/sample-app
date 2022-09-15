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

Route::controller(TweetController::class)->prefix('tweets')->name('tweets')->group(function() {
    Route::get('/', 'list');
    Route::get('/create', 'create')->name('.create');
    Route::post('/create', 'store')->name('.store');
    Route::get('/{tweet}', 'show')->name('.show');
    Route::get('/{tweet}/edit', 'edit')->name('.edit');
    Route::put('/{tweet}', 'update')->name('.update');
    Route::delete('/{tweet}', 'destroy')->name('.destroy');
});
Route::controller(ReplyController::class)->prefix('replies')->name('replies')->group(function() {
    Route::get('/create', 'create')->name('.create');
    Route::post('/create', 'store')->name('.store');
    Route::delete('/{reply}', 'destroy')->name('.destroy');
});