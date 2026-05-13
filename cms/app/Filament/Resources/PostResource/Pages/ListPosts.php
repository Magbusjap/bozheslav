<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return collect(Post::LOCALES)
            ->mapWithKeys(fn (string $label, string $locale): array => [
                $locale => Tab::make($label)
                    ->badge(fn (): string|int => self::missingTranslationsCount($locale) ?: '✓')
                    ->badgeColor(fn (): string => self::missingTranslationsCount($locale) > 0 ? 'warning' : 'success')
                    ->modifyQueryUsing(fn (Builder $query): Builder => self::applyLocaleTabQuery($query, $locale)),
            ])
            ->all();
    }

    private static function applyLocaleTabQuery(Builder $query, string $locale): Builder
    {
        return $query
            ->fromSub(function ($query) use ($locale): void {
                $query
                    ->from('posts')
                    ->select('posts.*')
                    ->selectRaw(
                        "ROW_NUMBER() OVER (
                            PARTITION BY COALESCE(translation_group_id::text, id::text)
                            ORDER BY
                                CASE
                                    WHEN locale = ? THEN 0
                                    WHEN locale = 'ru' THEN 1
                                    WHEN locale = 'en' THEN 2
                                    WHEN locale = 'sr' THEN 3
                                    ELSE 4
                                END,
                                id
                        ) as locale_row_number",
                        [$locale]
                    );
            }, 'posts')
            ->select('posts.*')
            ->selectRaw('CASE WHEN posts.locale = ? THEN 0 ELSE 1 END as missing_current_locale', [$locale])
            ->selectRaw('? as selected_locale', [$locale])
            ->where('locale_row_number', 1);
    }

    private static function missingTranslationsCount(string $locale): int
    {
        return DB::query()
            ->fromSub(
                Post::query()
                    ->select('translation_group_id')
                    ->whereNotNull('translation_group_id')
                    ->groupBy('translation_group_id'),
                'translation_groups'
            )
            ->whereNotExists(function ($query) use ($locale): void {
                $query
                    ->selectRaw('1')
                    ->from('posts as translated_posts')
                    ->whereColumn('translated_posts.translation_group_id', 'translation_groups.translation_group_id')
                    ->where('translated_posts.locale', $locale);
            })
            ->count();
    }
}
