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
        Schema::table('glosses', function (Blueprint $table) {
            $table->unsignedInteger('position')->default(0)->after('gloss');
        });

        DB::table('glosses')
            ->select('id', 'term_id')
            ->orderBy('term_id')
            ->orderBy('id')
            ->get()
            ->groupBy('term_id')
            ->each(function ($glosses) {
                $glosses->values()->each(function ($gloss, int $index) {
                    DB::table('glosses')
                        ->where('id', $gloss->id)
                        ->update(['position' => $index + 1]);
                });
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('glosses', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }
};
