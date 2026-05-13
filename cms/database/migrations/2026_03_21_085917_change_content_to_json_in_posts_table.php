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
	    DB::statement('ALTER TABLE posts ALTER COLUMN content TYPE json USING content::json');
	}

    /**
     * Reverse the migrations.
     */
	    public function down(): void
	{
	    Schema::table('posts', function (Blueprint $table) {
	        $table->longText('content')->nullable()->change();
	    });
	}
};
