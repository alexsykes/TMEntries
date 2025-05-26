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
        Schema::create('series', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('clubID')->default(false);
            $table->unsignedBigInteger('adminID')->default(false);
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('classes')->nullable();
            $table->string('courses')->nullable();
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('series');
    }
};
