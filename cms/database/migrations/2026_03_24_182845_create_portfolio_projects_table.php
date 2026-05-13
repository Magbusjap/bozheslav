<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('portfolio_category_id')->nullable()->constrained()->nullOnDelete();
            $table->json('stack_tags')->nullable(); // теги стека
            $table->string('github_url')->nullable();
            $table->enum('link_type', ['demo', 'page', 'external'])->default('demo');
            $table->string('link_url')->nullable(); // ссылка demo или страница
            $table->string('link_label')->nullable(); // текст кнопки
            $table->unsignedBigInteger('cover_image')->nullable(); // ID из медиабиблиотеки
            $table->integer('sort_order')->default(0);
            $table->string('status')->default('published');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_projects');
    }
};