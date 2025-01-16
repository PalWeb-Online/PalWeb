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
        Schema::create('glosses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('term_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('gloss');
            $table->string('attribute')->nullable();
            $table->string('structure')->nullable();
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
        Schema::dropIfExists('glosses');
    }
};
