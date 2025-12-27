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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->string('group')->default('main');
            $table->foreignId('unit_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('position');
            $table->string('slug')->unique()->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('document')->nullable();
            $table->foreignId('deck_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('activity_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('dialog_id')->nullable()->constrained()->nullOnDelete();
            $table->json('unlock_conditions')->nullable();
            $table->boolean('published')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
