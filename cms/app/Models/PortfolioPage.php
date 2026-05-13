<?php

namespace App\Models;

use App\Models\Concerns\HasLocaleTranslations;
use Illuminate\Database\Eloquent\Model;

class PortfolioPage extends Model
{
    use HasLocaleTranslations;

    protected $fillable = [
        'locale',
        'translation_group_id',
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
