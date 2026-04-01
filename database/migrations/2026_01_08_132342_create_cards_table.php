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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('term_id')->constrained()->cascadeOnDelete();
            $table->integer('repetitions')->default(0);
            $table->integer('lapses')->default(0);
            $table->integer('step')->default(0)->nullable();
            $table->integer('interval_days')->default(0);
            $table->float('ease_factor')->default(2.5);
            $table->date('due_at')->nullable();
            $table->date('last_reviewed_at')->nullable();
            $table->timestamps();
            $table->timestamp('suspended_at')->nullable();
            $table->unique(['user_id', 'term_id']);
            $table->index(['user_id', 'due_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
