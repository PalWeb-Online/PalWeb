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
        Schema::table('term_relative', function (Blueprint $table) {
            $table->foreignId('gloss_id')->after('relative_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('term_relative', function (Blueprint $table) {
            $table->dropForeign(['gloss_id']);
            $table->dropColumn('gloss_id');
        });
    }
};
