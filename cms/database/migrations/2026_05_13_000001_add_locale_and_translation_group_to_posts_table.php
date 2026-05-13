<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (! Schema::hasColumn('posts', 'locale')) {
                $table->string('locale', 5)->default('ru');
            }

            if (! Schema::hasColumn('posts', 'translation_group_id')) {
                $table->uuid('translation_group_id')->nullable()->index();
            }
        });

        DB::table('posts')
            ->whereNull('translation_group_id')
            ->orderBy('id')
            ->chunkById(100, function ($posts) {
                foreach ($posts as $post) {
                    DB::table('posts')
                        ->where('id', $post->id)
                        ->update(['translation_group_id' => (string) Str::uuid()]);
                }
            });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropUnique('posts_slug_unique');
            $table->unique(['locale', 'slug'], 'posts_locale_slug_unique');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropUnique('posts_locale_slug_unique');
            $table->dropColumn(['locale', 'translation_group_id']);
            $table->unique('slug', 'posts_slug_unique');
        });
    }
};
