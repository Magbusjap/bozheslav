<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class BotLogs extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Логи';
    protected static ?string $navigationGroup = 'Боты и AI';
    protected static ?int $navigationSort = 4;
    protected static string $view = 'filament.pages.bot-logs';

    public array $logs = [];
    public ?string $lastCommandAt = null;

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $this->logs = DB::table('bot_logs')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get(['level', 'event', 'message', 'created_at'])
            ->toArray();

        $lastCommand = DB::table('bot_logs')
            ->where('event', 'user_command')
            ->orderBy('created_at', 'desc')
            ->first();

        $this->lastCommandAt = $lastCommand ? $lastCommand->created_at : null;
    }
}