<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DialectSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('dialects')->insert(['name' => 'General Palestinian']);
        DB::table('dialects')->insert(['name' => 'Urban Palestinian']);
        DB::table('dialects')->insert(['name' => 'Rural Palestinian']);
        DB::table('dialects')->insert(['name' => 'Central Palestinian']);
        DB::table('dialects')->insert(['name' => 'Northern Palestinian']);
        DB::table('dialects')->insert(['name' => 'Palestinian Bedouin']);
        DB::table('dialects')->insert(['name' => 'Palestinian Druze']);
        DB::table('dialects')->insert(['name' => 'Central Urban Palestinian']);
        DB::table('dialects')->insert(['name' => 'Northern Urban Palestinian']);
        DB::table('dialects')->insert(['name' => 'Central Rural Palestinian']);
        DB::table('dialects')->insert(['name' => 'Northern Rural Palestinian']);

        DB::table('dialect_hierarchy')->insert(['ancestor_id' => '1', 'descendant_id' => '2']);
        DB::table('dialect_hierarchy')->insert(['ancestor_id' => '1', 'descendant_id' => '3']);
        DB::table('dialect_hierarchy')->insert(['ancestor_id' => '1', 'descendant_id' => '4']);
        DB::table('dialect_hierarchy')->insert(['ancestor_id' => '1', 'descendant_id' => '5']);
        DB::table('dialect_hierarchy')->insert(['ancestor_id' => '1', 'descendant_id' => '6']);
        DB::table('dialect_hierarchy')->insert(['ancestor_id' => '1', 'descendant_id' => '7']);
        DB::table('dialect_hierarchy')->insert(['ancestor_id' => '1', 'descendant_id' => '8']);
        DB::table('dialect_hierarchy')->insert(['ancestor_id' => '1', 'descendant_id' => '9']);
        DB::table('dialect_hierarchy')->insert(['ancestor_id' => '1', 'descendant_id' => '10']);
        DB::table('dialect_hierarchy')->insert(['ancestor_id' => '1', 'descendant_id' => '11']);
        DB::table('dialect_hierarchy')->insert(['ancestor_id' => '2', 'descendant_id' => '8']);
        DB::table('dialect_hierarchy')->insert(['ancestor_id' => '2', 'descendant_id' => '9']);
        DB::table('dialect_hierarchy')->insert(['ancestor_id' => '3', 'descendant_id' => '10']);
        DB::table('dialect_hierarchy')->insert(['ancestor_id' => '3', 'descendant_id' => '11']);
        DB::table('dialect_hierarchy')->insert(['ancestor_id' => '4', 'descendant_id' => '8']);
        DB::table('dialect_hierarchy')->insert(['ancestor_id' => '4', 'descendant_id' => '10']);
        DB::table('dialect_hierarchy')->insert(['ancestor_id' => '5', 'descendant_id' => '9']);
        DB::table('dialect_hierarchy')->insert(['ancestor_id' => '5', 'descendant_id' => '11']);
    }
}
