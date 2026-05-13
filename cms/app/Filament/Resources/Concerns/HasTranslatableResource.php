<?php

namespace App\Filament\Resources\Concerns;

use App\Models\Post;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

trait HasTranslatableResource
{
    protected static function localeFormSelect(): Forms\Components\Select
    {
        return Forms\Components\Select::make('locale')
            ->label('Язык')
            ->options(Post::LOCALES)
            ->default('ru')
            ->required();
    }

    protected static function localeTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('locale')
            ->label('Язык')
            ->badge()
            ->formatStateUsing(fn (string $state, Model $record): string => $record->isTranslationPlaceholder()
                ? $record->localeLabel($record->selectedLocale()) . ': клонировать'
                : $record->localeLabel($state))
            ->color(fn (string $state, Model $record): string => self::localeColor(
                $record->isTranslationPlaceholder() ? $record->selectedLocale() : $state
            ))
            ->tooltip(fn (Model $record): ?string => $record->isTranslationPlaceholder()
                ? 'Создать версию ' . $record->localeLabel($record->selectedLocale()) . ' на основе ' . $record->localeLabel()
                : null)
            ->action(fn (Model $record) => $record->isTranslationPlaceholder()
                ? redirect(static::getTranslationCreateUrl($record))
                : null);
    }

    protected static function placeholderCellAttributes(Model $record): array
    {
        return $record->isTranslationPlaceholder()
            ? ['style' => 'opacity: .55;']
            : [];
    }

    protected static function placeholderDescription(Model $record): ?string
    {
        return $record->isTranslationPlaceholder()
            ? 'Нет версии ' . $record->localeLabel($record->selectedLocale()) . '. Показана основа ' . $record->localeLabel() . '.'
            : null;
    }

    protected static function translatableRecordUrl(Model $record): ?string
    {
        return $record->isTranslationPlaceholder()
            ? null
            : static::getUrl('edit', ['record' => $record]);
    }

    public static function getTranslationCreateUrl(Model $source): string
    {
        $targetLocale = $source->selectedLocale();
        $model = static::getModel();

        $existingTranslation = $source->translation_group_id
            ? $model::query()
                ->where('translation_group_id', $source->translation_group_id)
                ->where('locale', $targetLocale)
                ->first()
            : null;

        if ($existingTranslation) {
            return static::getUrl('edit', ['record' => $existingTranslation]);
        }

        return static::getUrl('create', [
            'clone_from' => $source->getKey(),
            'locale' => $targetLocale,
        ]);
    }

    public static function translationCloneData(Model $source, string $targetLocale): array
    {
        $data = collect(static::translationCloneFields())
            ->mapWithKeys(fn (string $field): array => [$field => $source->{$field}])
            ->all();

        $data['locale'] = $targetLocale;

        if (array_key_exists('slug', $data)) {
            $data['slug'] = static::uniqueSlugForLocale($source->slug, $targetLocale);
        }

        if (array_key_exists('status', $data)) {
            $data['status'] = 'draft';
        }

        return $data;
    }

    public static function uniqueSlugForLocale(string $slug, string $locale): string
    {
        $model = static::getModel();
        $baseSlug = Str::slug($slug) ?: 'record';
        $uniqueSlug = $baseSlug;
        $suffix = 2;

        while ($model::query()->where('locale', $locale)->where('slug', $uniqueSlug)->exists()) {
            $uniqueSlug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $uniqueSlug;
    }

    protected static function localeColor(string $locale): string
    {
        return match ($locale) {
            'ru' => 'danger',
            'en' => 'info',
            'sr' => 'success',
            default => 'gray',
        };
    }

    protected static function slugUniqueRule(): \Closure
    {
        return fn (Unique $rule, callable $get) => $rule->where('locale', $get('locale'));
    }
}
