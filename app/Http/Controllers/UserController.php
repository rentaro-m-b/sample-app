<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Block;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Requests\Auth\LoginRequest;


class UserController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'age' => ['integer', 'nullable'],
            'address' => ['string', 'nullable', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
        ]);
        if ($validator->fails()) {
            $response = response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ],400);
            throw new HttpResponseException($response);
        }
        $data = $validator->safe()->only([
            'name',
            'age',
            'address',
            'email',
            'password'
        ]);
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        return $user;
        event(new Registered($user));
        Auth::login($user);
        $responseBody = 'ok';
        $responseCode = 200;

        return response($responseBody, $responseCode)
            ->header('Content-Type', 'text/plain');
    }

    public function login(LoginRequest $request)
    {
        return $request->only('email', 'password');
        $request->authenticate();

        $request->session()->regenerate();

        $responseBody = 'ok';
        $responseCode = 200;

        return response($responseBody, $responseCode)
            ->header('Content-Type', 'text/plain');
    }

    public function logout(Request $request)
    {
        return $request;
        Auth::guard('web')->logout();
        
        $request->session()->invalidate();

        $request->session()->regenerateToken();
        $responseBody = 'ok';
        $responseCode = 200;
        
        return response($responseBody, $responseCode)
            ->header('Content-Type', 'text/plain');
    }

    public function add_block(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['integer', Rule::exists('users', 'id')],
            'blocked_user_id' => ['integer', Rule::exists('users', 'id')],
        ]);

        Block::create($data);
    }

    public function delete_block(Block $block)
    {
        $block->delete();
    }

    public function add_follow(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['integer', Rule::exists('users', 'id')],
            'followee_id' => ['integer', Rule::exists('users', 'id')],
        ]);

        Block::create($data);
    }

    public function delete_follow(Follow $follow)
    {
        $follow->delete();
    }

    /**
     * ユーザー一覧
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */

    public function search(Request $request)
    {
        $users = User::paginate(20);
        $key = $request->input('key');
        $query = User::query();
        $query->where('name', 'like', '%'.$key.'%');
        $users = $query->paginate(20);

        return $users;
    }
}
