<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Clean expired trash records daily
Schedule::command('trash:clean')->daily();

// Clear Laravel cache weekly
Schedule::command('cache:clear')->weekly();

// Clear view cache weekly  
Schedule::command('view:clear')->weekly();
