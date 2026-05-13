<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class BotAnalytics extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Аналитика';
    protected static ?string $navigationGroup = 'Боты и AI';
    protected static ?int $navigationSort = 3;
    protected static string $view = 'filament.pages.bot-analytics';

    public array $stats = [];
    public array $angelJobs = [];
    public array $demonJobs = [];
    public array $bySource = [];
    public array $byDay = [];

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        // Общая статистика
        $this->stats = [
            'total'          => DB::table('bot_jobs')->count(),
            'taken'          => DB::table('bot_jobs')->where('verdict', 'take')->count(),
            'skipped'        => DB::table('bot_jobs')->where('verdict', 'skip')->count(),
            'overridden'     => DB::table('bot_jobs')->where('human_override', true)->count(),
            'paid'           => DB::table('bot_jobs')->where('is_paid', true)->count(),
            'total_earned'   => DB::table('bot_jobs')->where('is_paid', true)->sum('price_paid'),
        ];

        // Ангел — взял и выполнил
        $this->angelJobs = DB::table('bot_jobs')
            ->where('verdict', 'take')
            ->orderBy('found_at', 'desc')
            ->limit(20)
            ->get(['id', 'title', 'budget', 'source', 'category', 'human_override', 'is_paid', 'price_paid', 'found_at'])
            ->toArray();

        // Демон — пропустил
        $this->demonJobs = DB::table('bot_jobs')
            ->where('verdict', 'skip')
            ->where('human_override', true)
            ->orderBy('found_at', 'desc')
            ->limit(20)
            ->get(['id', 'title', 'budget', 'source', 'category', 'found_at'])
            ->toArray();

        // По площадкам
        $this->bySource = DB::table('bot_jobs')
            ->select('source', DB::raw('count(*) as total'), DB::raw("sum(case when verdict='take' then 1 else 0 end) as taken"))
            ->groupBy('source')
            ->get()
            ->toArray();

        // По дням (последние 14 дней)
        $this->byDay = DB::table('bot_jobs')
            ->select(DB::raw('DATE(found_at) as day'), DB::raw('count(*) as total'), DB::raw("sum(case when verdict='take' then 1 else 0 end) as taken"))
            ->where('found_at', '>=', now()->subDays(14))
            ->groupBy('day')
            ->orderBy('day', 'asc')
            ->get()
            ->toArray();
    }
}