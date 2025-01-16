<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attributes', function (Blueprint $table) {
            $table->string('model')->after('id');
            $table->string('category')->nullable()->after('attribute');
        });

        DB::table('attributes')->insert([
            'model' => 'gloss',
            'attribute' => 'auxiliary',
        ]);
        DB::table('attributes')->insert([
            'model' => 'gloss',
            'attribute' => 'participle',
        ]);
        DB::table('attributes')->insert([
            'model' => 'gloss',
            'attribute' => 'unaccusative',
            'category' => 'isPatient',
        ]);
        DB::table('attributes')->insert([
            'model' => 'gloss',
            'attribute' => 'mediopassive',
            'category' => 'isPatient',
        ]);
        DB::table('attributes')->insert([
            'model' => 'gloss',
            'attribute' => 'reflexive',
            'category' => 'isPatient',
        ]);
        DB::table('attributes')->insert([
            'model' => 'gloss',
            'attribute' => 'reciprocal',
            'category' => 'isPatient',
        ]);
        DB::table('attributes')->insert([
            'model' => 'gloss',
            'attribute' => 'unergative',
            'category' => 'noPatient',
        ]);
        DB::table('attributes')->insert([
            'model' => 'gloss',
            'attribute' => 'stative',
            'category' => 'noPatient',
        ]);
        DB::table('attributes')->insert([
            'model' => 'gloss',
            'attribute' => 'copular',
            'category' => 'noPatient',
        ]);
        DB::table('attributes')->insert([
            'model' => 'gloss',
            'attribute' => 'transitive',
            'category' => 'hasObject',
        ]);
        DB::table('attributes')->insert([
            'model' => 'gloss',
            'attribute' => 'ditransitive',
            'category' => 'hasObject',
        ]);
        DB::table('attributes')->insert([
            'model' => 'gloss',
            'attribute' => 'causative',
            'category' => 'hasObject',
        ]);
        DB::table('attributes')->insert([
            'model' => 'gloss',
            'attribute' => 'dative',
            'category' => 'hasObject',
        ]);

        foreach (\App\Models\Attribute::where('model', '!=', 'gloss')->get() as $attribute) {
            $attribute->update(['model' => 'term']);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attributes', function (Blueprint $table) {
            $table->dropColumn('model');
            $table->dropColumn('category');
        });
    }
};
