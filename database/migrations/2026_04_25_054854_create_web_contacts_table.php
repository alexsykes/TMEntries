<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('web_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('');
            $table->string('email')->default('');
            $table->text('message')->default('');
            $table->string('category')->nullable();
            $table->string('ip_address')->default('0.0.0.0');
            $table->longText('response')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->boolean('closed')->default(false);
            $table->string('action')->nullable();
            $table->string('action_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('web_contacts');
    }
};
