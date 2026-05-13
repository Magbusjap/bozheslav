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
        Schema::create('bot_leads', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_email')->nullable();
            $table->string('website')->nullable();
            $table->string('source')->nullable(); // hh, 2gis, avito, manual
            $table->string('niche')->nullable(); // кафе, салон, магазин
            $table->boolean('has_website')->default(false);
            $table->string('site_quality')->nullable(); // bad, none, ok
            $table->string('status')->default('new'); // new, sent, replied, rejected
            $table->text('ai_analysis')->nullable();
            $table->boolean('sent_to_telegram')->default(false);
            $table->timestamp('email_sent_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_leads');
    }
};
