<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\Reply;
use App\Models\Bookmark;
use App\Models\Like;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Schema;
use App\Http\Requests\StorePostRequest;


class TweetController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except([
            'list',
            'update',
            'destroy',
            'store_reply',
            'destroy_reply',
            'store_bookmark',
            'destroy_bookmark',
            'store_like', 
            'destroy_like', 
            'store', 
            'store_like',
            'search',
        ]);
    }

    public function list()
    {
        $tweets = Tweet::orderByDesc('created_at')
            ->paginate(5);
        $replies = Reply::orderByDesc('created_at')
            ->paginate(5);
        $responseBody = [$tweets, $replies];
        $responseCode = 200;

        return response($responseBody, $responseCode)
            ->header('Content-Type', 'application/json');
    }

    /**
     * 新ブログポストの保存
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {   
        $validated = $request->validated();

        // バリデーション済み入力データの一部を取得
        $validated = $request->safe()->only(['user_id', 'contents']);
        // $validated = $request->safe()->except(['user_id', 'contents']);

        // if (!$request->filled('user_id')) {
        //     $responseBody = 'user_id required.';
        //     $responseCode = 400;
        // } elseif (!$request->has('contents')) {
        //     $responseBody = 'contents requierd.';
        //     $responseCode = 400;
        // } else {
        //     $data = $request->validate([
        //         'contents' => ['required', 'string', 'max:400'],
        //         'user_id' => ['integer', Rule::exists('users', 'id')],
        //     ]);
        //     Tweet::create($data);
        //     $responseBody = 'ok';
        //     $responseCode = 200;
        // }
        return $validated;
        // return response($responseBody, $responseCode)
        //     ->header('Content-Type', 'text/plain');
    }

    public function update(Request $request, Tweet $tweet)
    {
        if (!$request->has('contents')) {
            $responseBody = 'contents requierd.';
            $responseCode = 400;
        } else {
            $data = $request->validate([
                'contents' => ['required', 'string', 'max:400'],
            ]);
            $tweet->contents = $data['contents'];
            $tweet->save();
            $responseBody = 'ok';
            $responseCode = 200;
        }

        return response($responseBody, $responseCode)
            ->header('Content-Type', 'text/plain');
    }

    public function destroy(Tweet $tweet)
    {   
        Bookmark::where('tweet_id', $tweet->id)->delete();
        Like::where('tweet_id', $tweet->id)->delete();
        Schema::disableForeignKeyConstraints();
        $tweet->delete();
        Schema::enableForeignKeyConstraints();
        $responseBody = 'ok';
        $responseCode = 200;
        
        return response($responseBody, $responseCode)
            ->header('Content-Type', 'text/plain');
    }

    public function store_reply(Request $request)
    {
        if (!$request->filled('tweet_id')) {
            $responseBody = 'tweet_id required.';
            $responseCode = 400;
        } elseif (!$request->has('contents')) {
            $responseBody = 'contents requierd.';
            $responseCode = 400;
        } else {
            $data = $request->validate([
                'tweet_id' => ['integer', Rule::exists('tweets', 'id')],
                'contents' => ['required', 'string', 'max:400'],
            ]);
            Reply::create($data);
            $responseBody = 'ok';
            $responseCode = 200;
        }

        return response($responseBody, $responseCode)
            ->header('Content-Type', 'text/plain');
    }

    public function destroy_reply(Reply $reply)
    {
        $reply->delete();
        $responseBody = 'ok';
        $responseCode = 200;

        return response($responseBody, $responseCode)
            ->header('Content-Type', 'text/plain');
    }

    public function store_bookmark(Request $request)
    {
        if (!$request->filled('user_id')) {
            $responseBody = 'user_id required.';
            $responseCode = 400;
        } elseif (!$request->filled('tweet_id')) {
            $responseBody = 'tweet_id requierd.';
            $responseCode = 400;
        } else {
            $data = $request->validate([
                'user_id' => ['integer', Rule::exists('users', 'id')],
                'tweet_id' => ['integer', Rule::exists('tweets', 'id')],
            ]);
            Bookmark::create($data);
            $responseBody = 'ok';
            $responseCode = 200;
        }
        
        return response($responseBody, $responseCode)
            ->header('Content-Type', 'text/plain');
    }

    public function destroy_bookmark(Bookmark $bookmark)
    {   
        $bookmark->delete();
        $responseBody = 'ok';
        $responseCode = 200;

        return response($responseBody, $responseCode)
            ->header('Content-Type', 'text/plain');
    }

    public function store_like(Request $request)
    {
        if (!$request->filled('user_id')) {
            $responseBody = 'user_id required.';
            $responseCode = 400;
        } elseif (!$request->filled('tweet_id')) {
            $responseBody = 'tweet_id requierd.';
            $responseCode = 400;
        } else {
            $data = $request->validate([
                'user_id' => ['integer', Rule::exists('users', 'id')],
                'tweet_id' => ['integer', Rule::exists('tweets', 'id')],
            ]);
            Like::create($data);
            $responseBody = 'ok';
            $responseCode = 200;
        }

        return response($responseBody, $responseCode)
            ->header('Content-Type', 'text/plain');
    }

    public function destroy_like(Like $like)
    {
        $like->delete();
        $responseBody = 'ok';
        $responseCode = 200;

        return response($responseBody, $responseCode)
            ->header('Content-Type', 'text/plain');
    }

    public function search(Request $request)
    {
        if (!$request->filled('key')) {
            $responseBody = 'key required.';
            $responseCode = 400;
        } else {
            $tweets = Tweet::paginate(20);
            $key = $request->key;
            $tweets = Tweet::query()->where('contents', 'like', '%'.$key.'%')->paginate(20);
            $responseBody = $tweets;
            $responseCode = 200;
        }

        return response($responseBody, $responseCode)
            ->header('Content-Type', 'application/json');
    }
}
