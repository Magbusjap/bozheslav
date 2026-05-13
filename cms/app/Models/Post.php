<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'cover_image',
        'status',
        'category_id',
        'seo_title',
        'seo_description',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getCoverUrlAttribute(): ?string
    {
        if (!$this->cover_image) return null;
        $media = \Awcodes\Curator\Models\Media::find($this->cover_image);
        return $media ? $media->url : null;
    }

    public static function getMediaUrl(?int $id): ?string
    {
        if (!$id) return null;
        $media = \Awcodes\Curator\Models\Media::find($id);
        return $media?->url;
    }
}