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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->integer('trial_id');

            $table->integer('day')->default(1);
            $table->integer('sheet')->default(1);
            $table->integer('rider');
            $table->integer('lap');
            $table->integer('section');
            $table->char('score')->nullable();
        });

        Schema::table('trials', function (Blueprint $table) {
            $table->boolean('isScoringSetup')->default(false);
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
