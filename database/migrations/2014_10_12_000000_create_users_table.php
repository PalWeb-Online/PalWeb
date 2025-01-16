<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ar_name')->nullable();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('avatar')->default('battix01.jpg');
            $table->string('home')->nullable();
            $table->text('bio')->nullable();
            $table->foreignId('dialect_id')->constrained();
            $table->boolean('private')->default(0);
            $table->string('language')->default('en');
            $table->string('password')->nullable();
            $table->string('discord_id')->nullable()->unique();
            $table->text('discord_token')->nullable();
            $table->text('discord_refresh_token')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
