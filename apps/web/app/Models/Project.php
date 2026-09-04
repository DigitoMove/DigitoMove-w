<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['title', 'slug', 'type', 'client', 'summary', 'description', 'cover_image', 'screenshots', 'website_url', 'demo_url', 'demo_username', 'demo_password', 'technologies', 'status', 'is_featured', 'published_at'];
    protected $casts = ['screenshots' => 'array', 'technologies' => 'array', 'is_featured' => 'boolean', 'published_at' => 'datetime'];
}
