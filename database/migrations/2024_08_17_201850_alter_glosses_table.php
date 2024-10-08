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
        Schema::table('glosses', function (Blueprint $table) {
            $table->dropColumn('attribute');
            $table->dropColumn('structure');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('glosses', function (Blueprint $table) {
            $table->string('attribute')->nullable();
            $table->string('structure')->nullable();
        });
    }
};
