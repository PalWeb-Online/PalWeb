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
        DB::table('gloss_relative')->orderBy('id')->chunkById(100, function ($relations) {
            foreach ($relations as $relation) {
                $gloss = \App\Models\Gloss::find($relation->gloss_id);

                DB::table('term_relative')->insert([
                    'term_id' => $gloss->term_id,
                    'relative_id' => $relation->relative_id,
                    'gloss_id' => $relation->gloss_id,
                    'type' => $relation->type,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        Schema::dropIfExists('gloss_relative');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('gloss_relative', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gloss_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('relative_id')->constrained('terms')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('type');
            $table->timestamps();
        });
    }
};
