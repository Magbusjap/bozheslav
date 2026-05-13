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
        Schema::create('bot_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('budget')->nullable();
            $table->string('link')->unique();
            $table->string('source')->default('kwork'); // kwork, fl, freelance
            $table->string('verdict')->nullable(); // take, skip, null
            $table->text('ai_analysis')->nullable();
            $table->integer('proposals')->default(0);
            $table->integer('hired_rate')->default(0);
            $table->decimal('price_paid', 10, 2)->nullable(); // оплачено
            $table->boolean('is_taken')->default(false); // взят
            $table->boolean('is_paid')->default(false); // оплачен
            $table->timestamp('found_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_jobs');
    }
};
