<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioPage extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'status',
        'seo_title',
        'seo_description',
    ];

    protected $casts = [
        'content' => 'array',
    ];
}