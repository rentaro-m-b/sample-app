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
        $this->middleware('auth')->except(['list', 'destroy', 'store_bookmark', 'store', 'store_like']);
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

    public function store(Request $request)
    {   
        $data = $request->validate([
            'contents' => ['required', 'string', 'max:400'],
            'user_id' => ['integer', Rule::exists('users', 'id')],
            //'parent_tweet_id' => ['integer', 'nullable', 'max:255']

        ]);
  
       Tweet::create($data);
    }

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

    public function destroy(Tweet $tweet)
    {   
        // return $tweet;
        $tweet->delete();
    }

    public function store_bookmark(Request $request)
    {
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

    public function store_like(Request $request)
    {
        //$this->authorize('edit', $tweet);
        $data = $request->validate([
            'user_id' => ['integer', Rule::exists('users', 'id')],
            'tweet_id' => ['integer', Rule::exists('tweets', 'id')],
            //'parent_tweet_id' => ['integer', 'nullable', 'max:255']
        ]);

        Like::create($data);
    }

    public function destroy_like(Like $like)
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
