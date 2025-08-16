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
        Schema::table('clubmails', function (Blueprint $table) {
            $table->string('originalName')->nullable();
            $table->string('mimeType')->nullable();
            $table->string('fileName')->nullable();
        });

        Schema::table('mailshots', function (Blueprint $table) {
            $table->string('originalName')->nullable();
            $table->string('mimeType')->nullable();
            $table->string('fileName')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mails', function (Blueprint $table) {
            //
        });
    }
};
