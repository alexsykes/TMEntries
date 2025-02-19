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
        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('trial_id');
            $table->unsignedSmallInteger('ridingNumber')->nullable();

            $table->string('name');
            $table->string('email');
            $table->string('licence')->nullable();
            $table->string('phone');
            $table->string('class');
            $table->string('course');
            $table->string('IPaddress');
            $table->string('make');
            $table->string('size')->nullable();
            $table->string('type');


            $table->date('dob')->nullable();
            $table->boolean('isYouth')->default(false);
            $table->boolean('accept')->default(false);

            $table->string('stripe_product_id')->nullable();
            $table->string('stripe_price_id')->nullable();
            $table->integer('status')->default(0);
            $table->string('token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entry');
    }
};
