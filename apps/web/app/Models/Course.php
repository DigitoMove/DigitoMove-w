<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['title', 'slug', 'level', 'summary', 'description', 'cover_image', 'tuition_fee', 'application_fee', 'duration', 'delivery_mode', 'status', 'applications_open'];
    protected $casts = ['tuition_fee' => 'decimal:2', 'application_fee' => 'decimal:2', 'applications_open' => 'boolean'];

    public function lessons() { return $this->hasMany(Lesson::class)->orderBy('sort_order'); }
    public function applications() { return $this->hasMany(CourseApplication::class); }
}
