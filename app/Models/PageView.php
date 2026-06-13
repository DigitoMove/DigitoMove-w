<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    public $timestamps = false;

    protected $fillable = ['page_id', 'path', 'referrer', 'visitor_hash', 'viewed_at'];
    protected $casts = ['viewed_at' => 'datetime'];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
