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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('entryID');
            $table->unsignedSmallInteger('ridingNumber')->nullable();

            $table->unsignedSmallInteger('cleans')->default(0);
            $table->unsignedSmallInteger('ones')->default(0)   ;
            $table->unsignedSmallInteger('twos')->default(0)   ;
            $table->unsignedSmallInteger('threes')->default(0)   ;
            $table->unsignedSmallInteger('fives')->default(0)   ;
            $table->unsignedSmallInteger('missed')->default(0)   ;

            $table->unsignedSmallInteger('total')->default(0)   ;
            $table->unsignedSmallInteger('position')->default(0)   ;

            $table->unsignedSmallInteger('timePenalties')->default(0)   ;

            $table->tinyInteger('status')->default(0)   ;

            $table->string('sectionScores')->nullable();
            $table->string('sequentialScores')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
