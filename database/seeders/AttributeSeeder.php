<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('attributes')->insert([
            'model' => 'term',
            'attribute' => 'masculine',
        ]);
        DB::table('attributes')->insert([
            'model' => 'term',
            'attribute' => 'feminine',
        ]);
        DB::table('attributes')->insert([
            'model' => 'term',
            'attribute' => 'plural',
        ]);
        DB::table('attributes')->insert([
            'model' => 'term',
            'attribute' => 'collective',
        ]);
        DB::table('attributes')->insert([
            'model' => 'term',
            'attribute' => 'demonym',
        ]);
        DB::table('attributes')->insert([
            'model' => 'term',
            'attribute' => 'defect',
        ]);
        DB::table('attributes')->insert([
            'model' => 'term',
            'attribute' => 'pseudo',
        ]);
        DB::table('attributes')->insert([
            'model' => 'term',
            'attribute' => 'clitic',
        ]);
        DB::table('attributes')->insert([
            'model' => 'term',
            'attribute' => 'idiom',
        ]);

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
    }
}
