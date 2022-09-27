<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\Reply;
use App\Models\Bookmark;
use App\Models\Like;
use App\Models\User;
use Illuminate\Validation\Rule;

class TweetController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['list', 'show', 'store_test']);
    }

    public function list()
    {
        $tweets = Tweet::orderByDesc('created_at')
            ->paginate(5);
        $replies = Reply::orderByDesc('created_at')
            // ->with('tweet')
            ->paginate(5);
        // return view('tweets.list', ['tweets' => $tweets, 'replies' => $replies]);
        return [$tweets, $replies];
    }

    // public function create()
    // {
    //     return view('tweets.create');
    // }

    public function store(Request $request)
    {   
        return 1;
        $data = $request->validate([
            'contents' => ['required', 'string', 'max:400'],
            'user_id' => ['integer', Rule::exists('users', 'id')],
            //'parent_tweet_id' => ['integer', 'nullable', 'max:255']

        ]);
  
       Tweet::create($data);
    }

    // public function show(Tweet $tweet)
    // {
    //     return view('tweets.show', ['tweet' => $tweet]);
    // }

    // public function edit(Tweet $tweet)
    // {
    //     return view('tweets.edit', ['tweet' => $tweet]);
    // }

    public function update(Request $request)
    {
        //$this->authorize('edit', $tweet);
        $data = $request->validate([
            'contents' => ['required', 'string', 'max:400'],
            'user_id' => ['integer', Rule::exists('users', 'id')],
            'tweet_id' => ['integer', Rule::exists('tweets', 'id')],
        ]);

        // $tweet->update($data);
        $tweet = Tweet::find($data['tweet_id']);
        $tweet->contents = $data['contents'];
        $tweet->save();
        return $tweet;
    }

    public function destroy(Request $request)
    {   
        $data = $request->validate([
            'tweet_id' => ['integer', Rule::exists('tweets', 'id')],
        ]);
        //$this->authorize('edit', $tweet);
        $tweet = Tweet::find($data['tweet_id']);
        $tweet->delete();
        return 'ok';
    }

    public function store_bookmark(Request $request)
    {
        //$this->authorize('edit', $tweet);
        $data = $request->validate([
            'user_id' => ['integer', Rule::exists('users', 'id')],
            'tweet_id' => ['integer', Rule::exists('tweets', 'id')],
        ]);

        Bookmark::create($data);
    }

    public function destroy_bookmark(Request $request)
    {
        //$this->authorize('edit', $tweet);
        $data = $request->validate([
            'bookmark_id' => ['integer', Rule::exists('bookmarks', 'id')],
        ]);
        
        $bookmark = Bookmark::find($data['bookmark_id']);
        $bookmark->delete();
    }

    public function add_like(Request $request)
    {
        //$this->authorize('edit', $tweet);
        $data = $request->validate([
            'user_id' => ['integer', Rule::exists('users', 'id')],
            'tweet_id' => ['integer', Rule::exists('tweets', 'id')],
            //'parent_tweet_id' => ['integer', 'nullable', 'max:255']
        ]);

        Like::create($data);
    }

    public function delete_like(Like $like)
    {
        //$this->authorize('edit', $tweet);
        $like->delete();
    }

    public function search(Request $request)
    {
        $tweets = Tweet::paginate(20);
        $key = $request->input('key');

        $query = Tweet::query();
        $query->where('contents', 'like', '%'.$key.'%');

        $tweets = $query->paginate(20);

        return $tweets;
    }
}
