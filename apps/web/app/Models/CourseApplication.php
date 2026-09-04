<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseApplication extends Model
{
    protected $fillable = ['course_id', 'user_id', 'name', 'email', 'phone', 'education_level', 'motivation', 'status'];
    public function course() { return $this->belongsTo(Course::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function payments() { return $this->hasMany(Payment::class); }
}
