<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clubmails', function (Blueprint $table) {
            $table->string('reply_to_address')->nullable();
            $table->string('reply_to_name')->nullable();
            $table->boolean('published')->default(true);
        });

        Schema::table('mailshots', function (Blueprint $table) {
            $table->string('reply_to_address')->nullable();
            $table->string('reply_to_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
