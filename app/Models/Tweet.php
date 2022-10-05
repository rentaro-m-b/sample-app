<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use HasFactory;

    protected $fillable = [
        'contents',
        'user_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function reply() {
        return $this->hasMany(Reply::class);
    }

    public function bookmark() {
        return $this->hasMany(Bookmark::class);
    }

    public function scopeWhereLike($query, string $attribute, string $keyword, int $position = 0) {
        $keyword = addcslashes($keyword, '\_%');
        $condition = [
            1 => "{$keyword}%",
            -1 => "%{$keyword}",
        ][$position] ?? "%{$keyword}%";

        return $query->where($attribute, 'LIKE', $condition);
    }
}
