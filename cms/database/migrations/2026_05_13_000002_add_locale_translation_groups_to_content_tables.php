<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    private array $tables = [
        'pages',
        'categories',
        'portfolio_projects',
        'portfolio_categories',
        'portfolio_pages',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) use ($table): void {
                if (! Schema::hasColumn($table, 'locale')) {
                    $blueprint->string('locale', 5)->default('ru')->after('id');
                }

                if (! Schema::hasColumn($table, 'translation_group_id')) {
                    $blueprint->uuid('translation_group_id')->nullable()->after('locale')->index();
                }
            });

            DB::table($table)
                ->whereNull('translation_group_id')
                ->orderBy('id')
                ->chunkById(100, function ($records) use ($table): void {
                    foreach ($records as $record) {
                        DB::table($table)
                            ->where('id', $record->id)
                            ->update(['translation_group_id' => (string) Str::uuid()]);
                    }
                });

            Schema::table($table, function (Blueprint $blueprint) use ($table): void {
                $blueprint->dropUnique("{$table}_slug_unique");
                $blueprint->unique(['locale', 'slug'], "{$table}_locale_slug_unique");
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) use ($table): void {
                $blueprint->dropUnique("{$table}_locale_slug_unique");
                $blueprint->unique('slug', "{$table}_slug_unique");
                $blueprint->dropColumn(['locale', 'translation_group_id']);
            });
        }
    }
};
