<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\Concerns\HasTranslatableResource;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use App\Traits\HasTrashAction;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    use HasTrashAction;
    use HasTranslatableResource;

    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                self::localeFormSelect(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(debounce: 800)
                    ->afterStateUpdated(function (?string $state, callable $set) {
                        if ($state === null || $state === '') {
                            $set('slug', '');
                            return;
                        }
                        $set('slug', \Illuminate\Support\Str::slug(transliterate($state)));
                    }),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true, modifyRuleUsing: self::slugUniqueRule()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->description(fn (Category $record): ?string => self::placeholderDescription($record))
                    ->extraAttributes(fn (Category $record): array => $record->isTranslationPlaceholder()
                        ? ['style' => 'cursor: default; text-decoration: none; opacity: .55;']
                        : [
                            'style' => 'cursor: pointer; text-decoration: none;',
                            'onmouseover' => 'this.style.textDecoration="underline"',
                            'onmouseout' => 'this.style.textDecoration="none"',
                        ]),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->extraAttributes(fn (Category $record): array => self::placeholderCellAttributes($record)),
                self::localeTableColumn(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordClasses(fn (Category $record): ?string => $record->isTranslationPlaceholder()
                ? 'bg-warning-50 dark:bg-warning-950/20'
                : null)
            ->recordUrl(fn (Category $record): ?string => self::translatableRecordUrl($record))
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (Category $record): bool => ! $record->isTranslationPlaceholder()),
                self::getTrashAction('Категории блога', 'name')
                    ->visible(fn (Category $record): bool => ! $record->isTranslationPlaceholder()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    self::getTrashBulkAction('Категории блога', 'name'),
                ]),
            ])
            ->checkIfRecordIsSelectableUsing(fn (Category $record): bool => ! $record->isTranslationPlaceholder());
    }

    public static function translationCloneFields(): array
    {
        return ['name', 'slug'];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
