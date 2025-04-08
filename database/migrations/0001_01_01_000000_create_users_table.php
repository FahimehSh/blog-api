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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->text('biography')->nullable();
            $table->string('mobile')->unique();
            $table->char('username')->unique()->nullable();
            $table->string('password')->nullable();
            $table->unsignedInteger('verification_code');
            $table->timestamp('mobile_verified_at')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->string('telegram_chat_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
