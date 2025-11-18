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
        Schema::create('club_members', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('club_id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->string('postcode');
            $table->string('emergency_contact');
            $table->string('emergency_number');
            $table->string('social');
            $table->enum('membership_type', ['new', 'renewal']);
            $table->enum('membership_category', ['rider', 'observer', 'life']);
            $table->boolean('accept')->default(false);
            $table->boolean('confirmed')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('club_member');
    }
};
