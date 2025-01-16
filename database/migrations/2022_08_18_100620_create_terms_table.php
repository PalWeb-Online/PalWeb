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
    public function up()
    {
        Schema::create('terms', function (Blueprint $table) {
            $table->id();
            $table->string('term');
            $table->string('translit')->collation('utf8mb4_0900_as_ci');
            $table->string('category');
            $table->string('slug')->collation('utf8mb4_0900_as_ci')->unique();
            $table->foreignId('root_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->json('etymology');
            $table->text('image')->nullable();
            $table->text('usage')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('terms');
    }
};
