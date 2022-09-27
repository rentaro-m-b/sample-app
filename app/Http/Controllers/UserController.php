<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Block;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function add_block(Request $request)
    {
        //$this->authorize('edit', $tweet);
        $data = $request->validate([
            'user_id' => ['integer', Rule::exists('users', 'id')],
            'blocked_user_id' => ['integer', Rule::exists('users', 'id')],
            //'parent_tweet_id' => ['integer', 'nullable', 'max:255']
        ]);

        Block::create($data);
    }

    public function delete_block(Block $block)
    {
        //$this->authorize('edit', $tweet);
        $block->delete();
    }

    public function add_follow(Request $request)
    {
        //$this->authorize('edit', $tweet);
        $data = $request->validate([
            'user_id' => ['integer', Rule::exists('users', 'id')],
            'followee_id' => ['integer', Rule::exists('users', 'id')],
            //'parent_tweet_id' => ['integer', 'nullable', 'max:255']
        ]);

        Block::create($data);
    }

    public function delete_follow(Follow $follow)
    {
        //$this->authorize('edit', $tweet);
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
