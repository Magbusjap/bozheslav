<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->string('url');                        // URL страницы
            $table->string('ip', 45)->nullable();         // IP адрес
            $table->string('country', 2)->nullable();     // RU, US, DE...
            $table->string('country_name')->nullable();   // Россия, США...
            $table->string('city')->nullable();           // Пермь, Москва...
            $table->float('latitude')->nullable();        // Координаты
            $table->float('longitude')->nullable();
            $table->string('user_agent')->nullable();     // Браузер/устройство
            $table->string('referer')->nullable();        // Откуда пришёл
            $table->string('device_type')->nullable();    // desktop/mobile/tablet
            $table->string('browser')->nullable();        // Chrome, Firefox...
            $table->string('os')->nullable();             // Windows, Linux...
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};