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
        Schema::create('gloss_relative', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gloss_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('relative_id')->constrained('terms')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('type');
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
        Schema::dropIfExists('gloss_relative');
    }
};
