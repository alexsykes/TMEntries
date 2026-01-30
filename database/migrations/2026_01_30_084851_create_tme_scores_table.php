<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tme_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trial_id');
            $table->integer('day');
            $table->integer('sheet');
            $table->integer('rider');
            $table->integer('lap');
            $table->integer('section');
            $table->char('score')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tme_scores');
    }
};
