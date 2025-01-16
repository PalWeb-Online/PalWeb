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
        Schema::create('files', function (Blueprint $table) {
            $table->string('uuid');
            $table->primary('uuid');

            $table->string('filename')->collation('utf8mb4_0900_as_ci');
            $table->string('path');
            $table->string('extension')->nullable();
            $table->string('fileable_type')->nullable();
            $table->foreignId('fileable_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
