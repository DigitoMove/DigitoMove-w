<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id', 'type', 'heading', 'body', 'cta_label', 'cta_url',
        'settings', 'sort_order', 'is_visible',
    ];

    protected $casts = ['settings' => 'array', 'is_visible' => 'boolean'];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
