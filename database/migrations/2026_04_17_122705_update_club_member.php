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
        Schema::table('club_members', function (Blueprint $table) {
            $table->tinyText('token')->nullable();
            $table->tinyText('amca_reg')->nullable();
            $table->tinyText('acu_reg')->nullable();
            $table->text('membership_category')->change();
            $table->date('dob')->nullable();
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
