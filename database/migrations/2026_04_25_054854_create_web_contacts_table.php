<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('web_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('category')->nullable();
            $table->string('from');
            $table->string('email');
            $table->text('content');
            $table->longText('response')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->boolean('closed');
            $table->string('action')->nullable();
            $table->string('action_by');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('web_contacts');
    }
};
