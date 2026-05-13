<?php

namespace App\Models\Concerns;

use App\Models\Post;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

trait HasLocaleTranslations
{
    protected static function bootHasLocaleTranslations(): void
    {
        static::creating(function ($model): void {
            $model->locale ??= in_array(App::getLocale(), array_keys(Post::LOCALES), true)
                ? App::getLocale()
                : 'ru';
            $model->translation_group_id ??= (string) Str::uuid();
        });
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

        return Post::LOCALES[$locale] ?? strtoupper($locale);
    }
}
