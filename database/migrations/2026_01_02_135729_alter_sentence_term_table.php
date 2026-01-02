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
        Schema::table('sentence_term', function (Blueprint $table) {
            $table->boolean('toggleable')->default(false)->after('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sentence_term', function (Blueprint $table) {
            $table->dropColumn('toggleable');
        });
    }
};
