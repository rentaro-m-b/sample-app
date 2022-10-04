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
