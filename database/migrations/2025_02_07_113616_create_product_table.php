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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->boolean('isYouth')->default(false);
            $table->boolean('isLive')->default(false);
            $table->boolean('isEntryFee')->default(true);
            $table->boolean('hasQuantity')->default(false);

            $table->unsignedBigInteger('trial_id');

            $table->string('product_name');
            $table->string('product_category')->nullable();
            $table->string('stripe_product_id');
            $table->string('stripe_price_id');
            $table->string('stripe_product_description');

            $table->unsignedBigInteger('purchases')->default(0);
            $table->unsignedBigInteger('version')->default(1);
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
