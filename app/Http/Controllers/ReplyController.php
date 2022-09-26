<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reply;
use App\Models\Tweet;
use Illuminate\Validation\Rule;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Tweet $tweet)
    {   
        return view('replies.create', ['tweet' => $tweet]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'contents' => ['required', 'string', 'max:400'],
            'tweet_id' => ['integer', Rule::exists('tweets', 'id')],
            //'parent_tweet_id' => ['integer', 'nullable', 'max:255']
        ]);
        Reply::create($data);
        return redirect()->route('tweets')->with('status', '投稿完了');
    }

    public function destroy(Request $request)
    {
        $reply = Reply::find($request->tweet_id);
        $reply->delete();
    }
}
