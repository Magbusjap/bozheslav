<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmailTemplateResource\Pages;
use App\Filament\Resources\EmailTemplateResource\RelationManagers;
use App\Models\EmailTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use App\Traits\HasTrashAction;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmailTemplateResource extends Resource
{
    use HasTrashAction;

    protected static ?string $model = EmailTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationLabel = 'Email Верстка';
    protected static ?string $modelLabel = 'Email Шаблон';
    protected static ?string $pluralModelLabel = 'Email Шаблоны';
    protected static ?string $navigationGroup = 'Рассылки'; 

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Основная информация')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Название шаблона')
                            ->placeholder('Например: Приветствие нового клиента')
                            ->required(),
                        Forms\Components\TextInput::make('subject')
                            ->label('Тема письма (Subject)')
                            ->placeholder('Добро пожаловать в наш сервис!')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Tabs::make('Content')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Конструктор MJML')
                            ->icon('heroicon-o-code-bracket')
                            ->schema([
                                Forms\Components\Textarea::make('mjml_content')
                                    ->label('Введите MJML код')
                                    ->rows(25)
                                    ->reactive()
                                    ->required(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Предпросмотр')
                            ->icon('heroicon-o-eye')
                            ->schema([
                                Forms\Components\ViewField::make('mjml_content')
                                    ->view('filament.forms.components.email-preview-iframe'),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Название шаблона')
                    ->searchable()
                    ->sortable()
                    ->extraAttributes(['style' => 'cursor: pointer; text-decoration: none;'])
                    ->extraAttributes(['onmouseover' => 'this.style.textDecoration="underline"',
                     'onmouseout' => 'this.style.textDecoration="none"']),
                Tables\Columns\TextColumn::make('subject')
                    ->label('Тема письма')
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Изменено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([/* ... */])
            ->actions([
            // new button for sending email
            Tables\Actions\Action::make('send_email')
                ->label('Отправить')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                // same input Email
                ->form([
                    Forms\Components\TextInput::make('receiver_email')
                        ->label('Email получателя')
                        ->email()
                        ->required()
                        ->default(auth()->user()->email),
                    Forms\Components\TextInput::make('user_name') 
                        ->label('Имя получателя')
                        ->placeholder('Напр: Алексей')
                        ->default('друг'), 
                ])
                        ->action(function (array $data, $record) {
                                try {
                                    $html = compileMjml($record->mjml_content);

                                    $html = str_replace('{{ name }}', $data['user_name'] ?? 'друг', $html);

                                    $plainText = strip_tags(str_replace(['<br>', '</td>', '</tr>'], "\n", $html));

                                    \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($data, $record, $html, $plainText) {
                                        $message->to($data['receiver_email'])
                                                ->subject($record->subject)
                                                ->html($html); 
                                                
                                        $message->text($plainText); 
                                    });

                                    \Filament\Notifications\Notification::make()
                                        ->title('Отправлено персонально для ' . ($data['user_name'] ?? 'получателя'))
                                        ->success()
                                        ->send();

                                } catch (\Exception $e) {
                                    \Filament\Notifications\Notification::make()
                                        ->title('Ошибка')
                                        ->body($e->getMessage())
                                        ->danger()
                                        ->send();
                                }
                            }),
                    
                    Tables\Actions\EditAction::make(),
                    self::getTrashAction('Email Шаблоны'),
                ]);
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
            'index' => Pages\ListEmailTemplates::route('/'),
            'create' => Pages\CreateEmailTemplate::route('/create'),
            'edit' => Pages\EditEmailTemplate::route('/{record}/edit'),
        ];
    }
}
