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
        Schema::create('mails', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('club_id')->nullable();
            $table->unsignedBigInteger('trial_id')->nullable();

            $table->string('subject');
            $table->string('category');
            $table->string('summary');
            $table->text('bodyText');
            $table->boolean('isLibrary')->default(false);
        });

        Schema::create('mailshots', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('mail_id');
            $table->unsignedBigInteger('club_id')->nullable();
            $table->unsignedBigInteger('sent_by')->nullable();

            $table->text('distribution')->nullable();
            $table->text('bodyText')->nullable();
            $table->string('subject')->nullable();
            $table->boolean('sent')->default(false);
            $table->time('sent_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail');
    }
};
