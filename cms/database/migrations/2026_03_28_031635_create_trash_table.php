<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trash', function (Blueprint $table) {
            $table->id();
            $table->string('model_type');        // App\Models\Post
            $table->string('model_label');       // Posts, Страницы, Проекты и т.д.
            $table->string('model_name');        // Название записи (title/name)
            $table->json('model_data');          // Все данные записи в JSON
            $table->unsignedBigInteger('original_id'); // ID оригинальной записи
            $table->foreignId('deleted_by')->nullable()->constrained('users'); // Кто удалил
            $table->timestamp('expires_at');     // Дата автоудаления (60 дней)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trash');
    }
};