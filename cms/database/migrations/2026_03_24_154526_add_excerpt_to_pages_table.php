<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->text('excerpt')->nullable()->after('slug');
        });

        // Меняем тип через сырой SQL с USING
        DB::statement('ALTER TABLE pages ALTER COLUMN content TYPE json USING content::json');
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('excerpt');
            $table->longText('content')->nullable()->change();
        });
    }
};