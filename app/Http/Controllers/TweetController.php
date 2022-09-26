<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\Reply;
use Illuminate\Validation\Rule;

class TweetController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['list', 'show']);
    }

    public function list()
    {
        $tweets = Tweet::orderByDesc('created_at')
            ->with('user')
            ->paginate(5);
        $replies = Reply::orderByDesc('created_at')
            ->with('tweet')
            ->paginate(5);
        return view('tweets.list', ['tweets' => $tweets, 'replies' => $replies]);
    }

    public function create()
    {
        return view('tweets.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'contents' => ['required', 'string', 'max:400'],
            'user_id' => ['integer', Rule::exists('users', 'id')],
            //'parent_tweet_id' => ['integer', 'nullable', 'max:255']

        ]);
  
       Tweet::create($data);
       return redirect()->route('tweets')->with('status', '投稿完了');
    }

    public function show(Tweet $tweet)
    {
        return view('tweets.show', ['tweet' => $tweet]);
    }

    public function edit(Tweet $tweet)
    {
        return view('tweets.edit', ['tweet' => $tweet]);
    }

    public function update(Request $request, Tweet $tweet)
    {
        //$this->authorize('edit', $tweet);
        $data = $request->validate([
            'contents' => ['required', 'string', 'max:400'],
            'user_id' => ['integer', Rule::exists('users', 'id')]
        ]);

        $tweet->update($data);
        return redirect()->route('tweets')->with('status', '編集完了'); 
    }

    public function destroy(Tweet $tweet)
    {
        //$this->authorize('edit', $tweet);
        $tweet->delete();
        return redirect()->route('tweets')->with('status', '削除完了');
    }
}
