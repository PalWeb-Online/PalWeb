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
        Schema::create('patterns', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('form')->nullable();
            $table->string('pattern')->collation('utf8mb4_0900_as_ci');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patterns');
    }
};
