<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Block;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class UserController extends Controller
{
    public function add_block(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'blocked_user_id' => ['required', 'integer', Rule::exists('users', 'id')],
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')],
        ]);
        if ($validator->fails()) {
            $response = response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ],400);
            throw new HttpResponseException($response);
        }
        $data = $validator->safe()->only(['blocked_user_id', 'user_id']);
        Block::create($data);

        $responseBody = 'ok';
        $responseCode = 200;
        
        return response($responseBody, $responseCode)
            ->header('Content-Type', 'text/plain');
    }

    public function delete_block(Block $block)
    {
        $block->delete();
        $responseBody = 'ok';
        $responseCode = 200;

        return response($responseBody, $responseCode)
            ->header('Content-Type', 'text/plain');
    }

    public function add_follow(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'followee_id' => ['required', 'integer', Rule::exists('users', 'id')],
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')],
        ]);
        if ($validator->fails()) {
            $response = response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ],400);
            throw new HttpResponseException($response);
        }
        $data = $validator->safe()->only(['followee_id', 'user_id']);
        Follow::create($data);

        $responseBody = 'ok';
        $responseCode = 200;
        
        return response($responseBody, $responseCode)
            ->header('Content-Type', 'text/plain');
    }

    public function delete_follow(Follow $follow)
    {
        $follow->delete();
        $responseBody = 'ok';
        $responseCode = 200;

        return response($responseBody, $responseCode)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * ユーザー一覧
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */

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
        $users = User::whereLike('name', $key)->paginate(20);

        $responseBody = $users;
        $responseCode = 200;

        return response($responseBody, $responseCode)
            ->header('Content-Type', 'application/json');
    }
}
