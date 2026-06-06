<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->boolean('isClubMember')->default(false);
            $table->string('otherClub')->nullable();
        });

        Schema::table('clubs', function (Blueprint $table) {
            $table->string('shortName')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entries', function (Blueprint $table) {
            //
        });
        Schema::table('clubs', function (Blueprint $table) {
            //
        });
    }
};
