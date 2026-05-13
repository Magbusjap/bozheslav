<?php

namespace App\Filament\Resources\EmailTemplateResource\Pages;

use App\Filament\Resources\EmailTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;
use Filament\Forms;

class EditEmailTemplate extends EditRecord
{
    protected static string $resource = EmailTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),

            Actions\Action::make('send_test')
                ->label('Отправить тест')
                ->color('success')
                ->icon('heroicon-o-paper-airplane')
                ->form([
                    Forms\Components\TextInput::make('receiver_email')
                        ->label('Email получателя')
                        ->email()
                        ->required()
                        ->default(auth()->user()->email),
                    Forms\Components\TextInput::make('user_name')
                        ->label('Имя для замены {{ name }}')
                        ->placeholder('Напр: Михаил')
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
                            ->title('Тестовое письмо отправлено!')
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
        ];
    }
}