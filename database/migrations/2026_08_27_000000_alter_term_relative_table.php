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
            $table->foreignId('reciprocal_id')
                ->after('id')
                ->nullable()
                ->constrained('term_relative')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });

        DB::statement('ALTER TABLE term_relative MODIFY type VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('term_relative', function (Blueprint $table) {
            $table->dropForeign(['reciprocal_id']);
            $table->dropColumn('reciprocal_id');
        });

        DB::table('term_relative')
            ->whereNull('type')
            ->update(['type' => 'unknown']);

        DB::statement('ALTER TABLE term_relative MODIFY type VARCHAR(255) NOT NULL');

    }
};
