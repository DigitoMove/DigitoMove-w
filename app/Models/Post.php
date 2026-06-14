<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['author_id', 'title', 'slug', 'excerpt', 'body', 'cover_image', 'category', 'status', 'reading_minutes', 'published_at'];
    protected $casts = ['published_at' => 'datetime'];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
