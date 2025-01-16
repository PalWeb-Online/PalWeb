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
        Schema::create('sentence_term', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sentence_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('term_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('gloss_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->string('sent_term')->nullable();
            $table->string('sent_translit')->nullable();
            $table->unsignedInteger('position');
            $table->index('position');
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
        Schema::dropIfExists('sentence_term');
    }
};
