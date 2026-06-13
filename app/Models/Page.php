<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'eyebrow', 'summary', 'status', 'seo_title',
        'seo_description', 'published_at',
    ];

    protected $casts = ['published_at' => 'datetime'];

    public function sections()
    {
        return $this->hasMany(PageSection::class)->orderBy('sort_order');
    }

    public function views()
    {
        return $this->hasMany(PageView::class);
    }
}
