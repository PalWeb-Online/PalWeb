<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            update deck_term
            set gloss_id = (
                select glosses.id
                from glosses
                where glosses.term_id = deck_term.term_id
                order by glosses.id
                limit 1
            )
            where gloss_id is null
        ");

        Schema::table('deck_term', function (Blueprint $table) {
            $table->unique(['deck_id', 'term_id', 'gloss_id']);
            $table->dropUnique('deck_term_deck_id_term_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deck_term', function (Blueprint $table) {
            $table->unique(['deck_id', 'term_id']);
            $table->dropUnique('deck_term_deck_id_term_id_gloss_id_unique');
        });
    }
};
