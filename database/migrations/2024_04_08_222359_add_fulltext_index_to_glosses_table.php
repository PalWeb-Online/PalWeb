<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('glosses') && Schema::hasColumn('glosses', 'gloss')) {
            DB::statement('ALTER TABLE glosses ADD FULLTEXT fulltext_index(gloss)');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE glosses DROP INDEX fulltext_index');
    }
};
