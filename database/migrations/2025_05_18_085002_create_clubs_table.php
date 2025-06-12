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
        Schema::create('clubs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name');
            $table->string('area')->nullable();;
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('description')->nullable();
            $table->string('facebook')->nullable();
            $table->string('website')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('club_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clubs');
    }
};
