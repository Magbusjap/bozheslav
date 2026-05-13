<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class Post extends Model
{
    public const LOCALES = [
        'ru' => 'RU',
        'en' => 'EN',
        'sr' => 'SR',
    ];

    protected $fillable = [
        'locale',
        'translation_group_id',
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

    protected static function booted(): void
    {
        static::creating(function (Post $post) {
            $post->locale ??= in_array(App::getLocale(), array_keys(self::LOCALES), true)
                ? App::getLocale()
                : 'ru';
            $post->translation_group_id ??= (string) Str::uuid();
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function translations()
    {
        return $this->hasMany(self::class, 'translation_group_id', 'translation_group_id')
            ->where('id', '!=', $this->id);
    }

    public function isTranslationPlaceholder(): bool
    {
        return (bool) $this->getAttribute('missing_current_locale');
    }

    public function selectedLocale(): string
    {
        return $this->getAttribute('selected_locale') ?: $this->locale;
    }

    public function localeLabel(?string $locale = null): string
    {
        $locale ??= $this->locale;

        return self::LOCALES[$locale] ?? strtoupper($locale);
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
