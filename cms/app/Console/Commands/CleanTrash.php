<?php

namespace App\Console\Commands;

use App\Models\Trash;
use Illuminate\Console\Command;

class CleanTrash extends Command
{
    protected $signature   = 'trash:clean';
    protected $description = 'Delete trash records older than 60 days';

    public function handle(): void
    {
        $count = Trash::where('expires_at', '<', now())->count();
        Trash::where('expires_at', '<', now())->delete();
        $this->info("Deleted {$count} expired trash records.");
    }
}