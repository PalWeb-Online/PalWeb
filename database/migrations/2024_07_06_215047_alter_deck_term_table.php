<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deck_term', function (Blueprint $table) {
            $table->foreignId('gloss_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deck_term', function (Blueprint $table) {
            $table->dropForeign('deck_term_gloss_id_foreign');
            $table->dropColumn('gloss_id');
        });
    }
};
