<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioProject extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'portfolio_category_id',
        'stack_tags',
        'github_url',
        'link_type',
        'link_url',
        'link_label',
        'cover_image',
        'sort_order',
        'status',
        'name',
    ];

    protected $casts = [
        'stack_tags' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(PortfolioCategory::class, 'portfolio_category_id');
    }

    public function getCoverUrlAttribute(): ?string
    {
        if (!$this->cover_image) return null;
        $media = \Awcodes\Curator\Models\Media::find($this->cover_image);
        return $media?->url;
    }
}