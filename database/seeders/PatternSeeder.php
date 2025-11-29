<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatternSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '1', 'pattern' => 'A1']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '1', 'pattern' => 'A1a']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '1', 'pattern' => 'A1u']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '1', 'pattern' => 'A1h']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '1', 'pattern' => 'A2']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '1', 'pattern' => 'A2i']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '1', 'pattern' => 'B1']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '1', 'pattern' => 'B1a']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '1', 'pattern' => 'B2']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '1', 'pattern' => 'B2i']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '1', 'pattern' => 'Ci']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '1', 'pattern' => 'Ca']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '1', 'pattern' => 'Cu']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '1', 'pattern' => 'DY']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '1', 'pattern' => 'DAi']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '1', 'pattern' => 'DAu']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '1', 'pattern' => 'DW']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '1', 'pattern' => 'ʔaža']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '2', 'pattern' => 'A']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '2', 'pattern' => 'B']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '2Q', 'pattern' => 'A']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '2Q', 'pattern' => 'B']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '3', 'pattern' => 'A']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '3', 'pattern' => 'B']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '4', 'pattern' => 'A']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '4', 'pattern' => 'B']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '4', 'pattern' => 'C']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '4', 'pattern' => 'D']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '5', 'pattern' => 'A']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '5', 'pattern' => 'B']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '5Q', 'pattern' => 'A']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '5Q', 'pattern' => 'B']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '6', 'pattern' => 'A']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '6', 'pattern' => 'B']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '7', 'pattern' => 'A']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '7', 'pattern' => 'B']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '7', 'pattern' => 'C']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '7', 'pattern' => 'D']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '7', 'pattern' => 'istanna']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '8', 'pattern' => 'A']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '8', 'pattern' => 'B']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '8', 'pattern' => 'C']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '8', 'pattern' => 'D']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '8', 'pattern' => 'E']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => '9', 'pattern' => 'A']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => 'X', 'pattern' => 'A']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => 'X', 'pattern' => 'B']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => 'X', 'pattern' => 'C']);
        DB::table('patterns')->insert(['type' => 'verbal', 'form' => 'X', 'pattern' => 'D']);

        DB::table('patterns')->insert(['type' => 'singular', 'form' => '1', 'pattern' => 'ap']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '1', 'pattern' => 'pp']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '2', 'pattern' => 'ap']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '2', 'pattern' => 'pp']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '2', 'pattern' => 'vn']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '2Q', 'pattern' => 'ap']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '2Q', 'pattern' => 'pp']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '2Q', 'pattern' => 'vn']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '3', 'pattern' => 'ap']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '3', 'pattern' => 'pp']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '3', 'pattern' => 'vn']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '4', 'pattern' => 'ap']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '4', 'pattern' => 'pp']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '4', 'pattern' => 'vn']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '5', 'pattern' => 'ap']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '5', 'pattern' => 'pp']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '5', 'pattern' => 'vn']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '5Q', 'pattern' => 'ap']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '5Q', 'pattern' => 'pp']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '5Q', 'pattern' => 'vn']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '6', 'pattern' => 'ap']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '6', 'pattern' => 'pp']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '6', 'pattern' => 'vn']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '7', 'pattern' => 'ap']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '7', 'pattern' => 'pp']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '7', 'pattern' => 'vn']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '8', 'pattern' => 'ap']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '8', 'pattern' => 'pp']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '8', 'pattern' => 'vn']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '9', 'pattern' => 'ap']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '9', 'pattern' => 'pp']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => '9', 'pattern' => 'vn']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => 'X', 'pattern' => 'ap']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => 'X', 'pattern' => 'pp']);
        DB::table('patterns')->insert(['type' => 'singular', 'form' => 'X', 'pattern' => 'vn']);

        // Named Patterns
        DB::table('patterns')->insert(['type' => 'singular', 'pattern' => 'ia']);
        DB::table('patterns')->insert(['type' => 'singular', 'pattern' => 'na']);
        DB::table('patterns')->insert(['type' => 'singular', 'pattern' => 'relative']);
        // Length 3
        DB::table('patterns')->insert(['type' => 'singular', 'pattern' => 'CLC']);
        DB::table('patterns')->insert(['type' => 'singular', 'pattern' => 'CVCC']);
        DB::table('patterns')->insert(['type' => 'singular', 'pattern' => 'CVCCe']);
        DB::table('patterns')->insert(['type' => 'singular', 'pattern' => 'CVCVC']);
        DB::table('patterns')->insert(['type' => 'singular', 'pattern' => 'CiCiC']);
        // Length 4
        DB::table('patterns')->insert(['type' => 'singular', 'pattern' => 'CCāC']);
        DB::table('patterns')->insert(['type' => 'singular', 'pattern' => 'CCīC']);
        DB::table('patterns')->insert(['type' => 'singular', 'pattern' => 'CCūC']);
        DB::table('patterns')->insert(['type' => 'singular', 'pattern' => 'CvCCvC']);
        DB::table('patterns')->insert(['type' => 'singular', 'pattern' => 'maCCvC']);
        // Length 5
        DB::table('patterns')->insert(['type' => 'singular', 'pattern' => 'CVCCLC']);
        DB::table('patterns')->insert(['type' => 'singular', 'pattern' => 'CaC:āC']);
        DB::table('patterns')->insert(['type' => 'singular', 'pattern' => 'CaC:īC']);
        DB::table('patterns')->insert(['type' => 'singular', 'pattern' => 'CaC:ūC']);

        // Sound
        DB::table('patterns')->insert(['type' => 'plural', 'pattern' => '-īn']);
        DB::table('patterns')->insert(['type' => 'plural', 'pattern' => '-āt']);
        // Broken
        DB::table('patterns')->insert(['type' => 'plural', 'pattern' => 'CCāC']);
        DB::table('patterns')->insert(['type' => 'plural', 'pattern' => 'CCīC']);
        DB::table('patterns')->insert(['type' => 'plural', 'pattern' => 'CCūC']);
        DB::table('patterns')->insert(['type' => 'plural', 'pattern' => 'ʔaCCāC']);
        DB::table('patterns')->insert(['type' => 'plural', 'pattern' => 'CuCaCa']);
        DB::table('patterns')->insert(['type' => 'plural', 'pattern' => 'CVCaC']);
        DB::table('patterns')->insert(['type' => 'plural', 'pattern' => 'ʔaCCiCe']);
        DB::table('patterns')->insert(['type' => 'plural', 'pattern' => 'CaCāCiC']);
        DB::table('patterns')->insert(['type' => 'plural', 'pattern' => 'CaCāCCe']);
        DB::table('patterns')->insert(['type' => 'plural', 'pattern' => 'CaCāCīC']);
        DB::table('patterns')->insert(['type' => 'plural', 'pattern' => 'CuC:āC']);
        DB::table('patterns')->insert(['type' => 'plural', 'pattern' => 'CuCuC']);
        DB::table('patterns')->insert(['type' => 'plural', 'pattern' => 'CVCCān']);
    }
}
