<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use HasFactory;

    //protected $guarded = ['id', 'created_at'];
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
}
