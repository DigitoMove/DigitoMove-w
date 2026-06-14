<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = ['course_id', 'title', 'slug', 'summary', 'content', 'video_url', 'resource_url', 'sort_order', 'is_preview'];
    protected $casts = ['is_preview' => 'boolean'];
    public function course() { return $this->belongsTo(Course::class); }
}
