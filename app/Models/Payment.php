<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['course_application_id', 'purpose', 'amount', 'currency', 'provider', 'reference', 'status', 'paid_at', 'metadata'];
    protected $casts = ['amount' => 'decimal:2', 'paid_at' => 'datetime', 'metadata' => 'array'];
    public function application() { return $this->belongsTo(CourseApplication::class, 'course_application_id'); }
}
