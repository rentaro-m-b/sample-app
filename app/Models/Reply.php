<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = [
        'contents',
        'tweet_id',
    ];

    public function tweet() {
        return $this->belongsTo(Tweet::class);
    }
}
