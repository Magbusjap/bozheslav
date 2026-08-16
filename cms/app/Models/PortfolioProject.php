<?php

namespace App\Models;

use App\Models\Concerns\HasLocaleTranslations;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PortfolioProject extends Model
{
    use HasLocaleTranslations;

    private static bool $isNormalizingSortOrder = false;

    protected $fillable = [
        'locale',
        'translation_group_id',
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

    protected static function booted(): void
    {
        static::saving(function (PortfolioProject $project): void {
            $project->slug = static::uniqueSlug(
                (string) ($project->slug ?: $project->title),
                $project->locale ?: 'ru',
                $project->exists ? $project->getKey() : null
            );
            $project->link_url = static::localizedPortfolioPageUrl(
                (string) $project->link_url,
                $project->locale ?: 'ru'
            );

            if (self::$isNormalizingSortOrder || ! $project->isDirty('sort_order')) {
                return;
            }

            $targetOrder = (int) $project->sort_order;

            if (! $project->exists && $targetOrder <= 0) {
                $targetOrder = ((int) static::where('locale', $project->locale ?: 'ru')->max('sort_order')) + 1;
            }

            $project->sort_order = max(1, $targetOrder);
        });

        static::saved(function (PortfolioProject $project): void {
            if (self::$isNormalizingSortOrder || ! $project->wasChanged('sort_order')) {
                return;
            }

            self::normalizeSortOrderForLocale($project->locale ?: 'ru', $project);
        });
    }

    public static function uniqueSlug(string $value, string $locale, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug(transliterate($value)) ?: 'project';
        $slug = $baseSlug;
        $suffix = 2;

        while (static::query()
            ->where('locale', $locale)
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists()
        ) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    public static function localizedPortfolioPageUrl(?string $url, string $locale): ?string
    {
        if (! is_string($url) || trim($url) === '') {
            return $url;
        }

        if (! preg_match('~^(?:https?://bozheslav\.com)?/(?:ru|en|sr)?/?portfolio/pages/([^/?#]+)~', $url, $matches)) {
            return $url;
        }

        $source = PortfolioPage::query()
            ->where('slug', $matches[1])
            ->first();

        if (! $source?->translation_group_id) {
            return $url;
        }

        $target = PortfolioPage::query()
            ->where('translation_group_id', $source->translation_group_id)
            ->where('locale', $locale)
            ->first();

        return $target ? url('/' . $locale . '/portfolio/pages/' . $target->slug) : $url;
    }

    public static function normalizeSortOrderForLocale(string $locale, ?PortfolioProject $priorityProject = null): void
    {
        self::$isNormalizingSortOrder = true;

        try {
            DB::transaction(function () use ($locale, $priorityProject): void {
                $query = static::query()
                    ->where('locale', $locale)
                    ->orderBy('sort_order')
                    ->orderBy('id');

                if ($priorityProject) {
                    $query->whereKeyNot($priorityProject->id);
                }

                $projects = $query->get(['id', 'sort_order'])->values();

                if ($priorityProject) {
                    $targetIndex = max(0, min((int) $priorityProject->sort_order - 1, $projects->count()));
                    $projects->splice($targetIndex, 0, [$priorityProject]);
                }

                $order = 1;

                foreach ($projects as $project) {
                    if ((int) $project->sort_order === $order) {
                        $order++;
                        continue;
                    }

                    static::whereKey($project->id)->update(['sort_order' => $order]);
                    $order++;
                }
            });
        } finally {
            self::$isNormalizingSortOrder = false;
        }
    }

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
