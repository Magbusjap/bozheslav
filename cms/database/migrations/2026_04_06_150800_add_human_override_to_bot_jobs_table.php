<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bot_jobs', function (Blueprint $table) {
            $table->boolean('human_override')->default(false)->after('verdict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bot_jobs', function (Blueprint $table) {
            $table->dropColumn('human_override');
        });
    }
};
