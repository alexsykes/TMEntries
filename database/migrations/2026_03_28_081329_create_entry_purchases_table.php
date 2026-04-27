<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('entry_purchases', function (Blueprint $table) {
            $table->unsignedBigInteger('entry_id');
            $table->integer('quantity');
            $table->string('stripe_price_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entry_purchases');
    }
};
