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
        DB::table('attributes')->insert(['attribute' => 'masculine']);
        DB::table('attributes')->insert(['attribute' => 'feminine']);
        DB::table('attributes')->insert(['attribute' => 'plural']);
        DB::table('attributes')->insert(['attribute' => 'collective']);
        DB::table('attributes')->insert(['attribute' => 'demonym']);
        DB::table('attributes')->insert(['attribute' => 'pseudo']);
        DB::table('attributes')->insert(['attribute' => 'defect']);
        DB::table('attributes')->insert(['attribute' => 'relative']);
        DB::table('attributes')->insert(['attribute' => 'participle']);
        DB::table('attributes')->insert(['attribute' => 'quantifier']);
        DB::table('attributes')->insert(['attribute' => 'complementizer']);
        DB::table('attributes')->insert(['attribute' => 'interrogative']);
        DB::table('attributes')->insert(['attribute' => 'clitic']);
        DB::table('attributes')->insert(['attribute' => 'idiom']);
    }
}
