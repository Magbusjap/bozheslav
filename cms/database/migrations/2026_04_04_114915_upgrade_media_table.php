<?php

use Awcodes\Curator\Facades\Curator;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = app(config('curator.model'))->getTable();

        if (! Schema::hasColumn($tableName, 'visibility')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->string('visibility')->default('public')->after('disk');
            });
        }
    }

    public function down(): void
    {
        $tableName = app(config('curator.model'))->getTable();

        if (Schema::hasColumn($tableName, 'visibility')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn(['visibility']);
            });
        }
    }
};
