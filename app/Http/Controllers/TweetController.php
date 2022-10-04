<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\Reply;
use App\Models\Bookmark;
use App\Models\Like;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class TweetController extends Controller
{

    public function __construct()
    {
        // 
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
    public function store(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'contents' => ['required', 'string', 'max:400'],
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')],
        ]);
        if ($validator->fails()) {
            $response = response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ],400);
            throw new HttpResponseException($response);
        }
        $data = $validator->safe()->only(['contents', 'user_id']);

        Tweet::create($data);
        $responseBody = 'ok';
        $responseCode = 200;
        
        return response($responseBody, $responseCode)
            ->header('Content-Type', 'text/plain');
    }

    public function update(Request $request, Tweet $tweet)
    {
        $validator = Validator::make($request->all(), [
            'contents' => ['required', 'string', 'max:400'],
        ]);
        if ($validator->fails()) {
            $response = response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ],400);
            throw new HttpResponseException($response);
        }
        $data = $validator->safe()->only(['contents']);

        $tweet->contents = $data['contents'];
        $tweet->save();
        $responseBody = 'ok';
        $responseCode = 200;

        return response($responseBody, $responseCode)
            ->header('Content-Type', 'text/plain');
    }

    public function destroy(Tweet $tweet)
    {   
        Bookmark::where('tweet_id', $tweet->id)->delete();
        Like::where('tweet_id', $tweet->id)->delete();
        $tweet->delete();
        $responseBody = 'ok';
        $responseCode = 200;
        
        return response($responseBody, $responseCode)
            ->header('Content-Type', 'text/plain');
    }

    public function store_reply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contents' => ['required', 'string', 'max:400'],
            'tweet_id' => ['required', 'integer', Rule::exists('tweets', 'id')],
        ]);
        if ($validator->fails()) {
            $response = response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ],400);
            throw new HttpResponseException($response);
        }
        $data = $validator->safe()->only(['contents', 'tweet_id']);

        Reply::create($data);
        $responseBody = 'ok';
        $responseCode = 200;

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
        $validator = Validator::make($request->all(), [
            'tweet_id' => ['required', 'integer', Rule::exists('tweets', 'id')],
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')],
        ]);
        if ($validator->fails()) {
            $response = response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ],400);
            throw new HttpResponseException($response);
        }
        $data = $validator->safe()->only(['tweet_id', 'user_id']);

        Bookmark::create($data);
        $responseBody = 'ok';
        $responseCode = 200;
        
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
        $validator = Validator::make($request->all(), [
            'tweet_id' => ['required', 'integer', Rule::exists('tweets', 'id')],
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')],
        ]);
        if ($validator->fails()) {
            $response = response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ],400);
            throw new HttpResponseException($response);
        }
        $data = $validator->safe()->only(['tweet_id', 'user_id']);
  
        Like::create($data);
        $responseBody = 'ok';
        $responseCode = 200;

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
        $validator = Validator::make($request->all(), [
            'key' => ['required'],
        ]);
        if ($validator->fails()) {
            $response = response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ],400);
            throw new HttpResponseException($response);
        }
        $data = $validator->safe()->only(['key']);

        $key = $data["key"];
        $tweets = Tweet::whereLike('contents', $key)->paginate(20);
        $responseBody = $tweets;
        $responseCode = 200;

        return response($responseBody, $responseCode)
            ->header('Content-Type', 'application/json');
    }
}
