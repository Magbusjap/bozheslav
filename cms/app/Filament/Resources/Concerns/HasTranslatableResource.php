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
            ->live()
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
            ->mapWithKeys(fn (string $field): array => [
                $field => static::translationCloneFieldValue($source, $field, $targetLocale),
            ])
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

    public static function createMissingTranslations(Model $source): void
    {
        if (! $source->translation_group_id) {
            return;
        }

        $model = static::getModel();

        foreach (array_keys(Post::LOCALES) as $locale) {
            if ($locale === $source->locale) {
                continue;
            }

            $exists = $model::query()
                ->where('translation_group_id', $source->translation_group_id)
                ->where('locale', $locale)
                ->exists();

            if ($exists) {
                continue;
            }

            $data = static::translationCloneData($source, $locale);
            $data['locale'] = $locale;
            $data['translation_group_id'] = $source->translation_group_id;

            if (array_key_exists('status', $data) && isset($source->status)) {
                $data['status'] = $source->status;
            }

            $model::query()->create($data);
        }
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

    protected static function translationCloneFieldValue(Model $source, string $field, string $targetLocale): mixed
    {
        $relationModel = static::translatedRelationFields()[$field] ?? null;

        if (is_string($relationModel)) {
            return static::translatedRelationKeyValue($source->{$field}, $relationModel, $targetLocale);
        }

        return $source->{$field};
    }

    protected static function translatedRelationFields(): array
    {
        return [];
    }

    protected static function translatedRelationKeyValue(mixed $key, string $relatedModelClass, string $targetLocale): mixed
    {
        if (! $key) {
            return $key;
        }

        $relatedRecord = $relatedModelClass::query()->find($key);

        if (! $relatedRecord) {
            return null;
        }

        if (! $relatedRecord->translation_group_id) {
            return $key;
        }

        return $relatedModelClass::query()
            ->where('translation_group_id', $relatedRecord->translation_group_id)
            ->where('locale', $targetLocale)
            ->value($relatedRecord->getKeyName());
    }
}
