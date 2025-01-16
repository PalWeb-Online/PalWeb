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
        Schema::create('dialect_hierarchy', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ancestor_id')->constrained('dialects');
            $table->foreignId('descendant_id')->constrained('dialects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('dialect_hierarchy');
    }
};
