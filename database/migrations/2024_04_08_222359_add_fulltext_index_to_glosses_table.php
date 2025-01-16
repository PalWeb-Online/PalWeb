<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('glosses') && Schema::hasColumn('glosses', 'gloss')) {
            DB::statement('ALTER TABLE glosses ADD FULLTEXT fulltext_index(gloss)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE glosses DROP INDEX fulltext_index');
    }
};
