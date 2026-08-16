<?php

namespace App\Filament\Resources\Pages\Concerns;

use App\Models\Post;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait HasLocaleTabs
{
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
        $table = $query->getModel()->getTable();

        return $query
            ->where('locale', $locale)
            ->select("{$table}.*")
            ->selectRaw('0 as missing_current_locale')
            ->selectRaw('? as selected_locale', [$locale]);
    }

    private static function missingTranslationsCount(string $locale): int
    {
        $model = static::getResource()::getModel();
        $table = (new $model())->getTable();

        return DB::query()
            ->fromSub(
                $model::query()
                    ->select('translation_group_id')
                    ->whereNotNull('translation_group_id')
                    ->groupBy('translation_group_id'),
                'translation_groups'
            )
            ->whereNotExists(function ($query) use ($locale, $table): void {
                $query
                    ->selectRaw('1')
                    ->from("{$table} as translated_records")
                    ->whereColumn('translated_records.translation_group_id', 'translation_groups.translation_group_id')
                    ->where('translated_records.locale', $locale);
            })
            ->count();
    }
}
