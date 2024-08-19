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
        Schema::create('attribute_gloss', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained()->onDelete('cascade');
            $table->foreignId('gloss_id')->constrained()->onDelete('cascade');
            $table->unique(['attribute_id', 'gloss_id']);
            $table->timestamps();
        });

        Artisan::call('refactor:glosses');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Artisan::call('rollback:migrate:attributes');

        Schema::dropIfExists('attribute_gloss');
    }
};
