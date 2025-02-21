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
        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name');
            $table->string('address');
            $table->string('phone')->nullable();
            $table->string('club')->nullable();
            $table->string('centre')->nullable();
            $table->text('directions')->nullable();
            $table->string('landowner');
            $table->text('notes')->nullable();
            $table->string('w3w')->nullable();

            $table->string('postcode')->nullable()  ;

            $table->decimal('latitude',10, 6)->nullable();;
            $table->decimal('longitude',10, 6)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};
