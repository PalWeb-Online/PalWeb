<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        (new BadgeSeeder)->run();
        (new DialectSeeder)->run();
        (new PatternSeeder)->run();
        (new AttributeSeeder)->run();
        (new UserSeeder)->run();
        (new RoleSeeder)->run();
        (new TermSeeder)->run();
        (new DeckSeeder)->run();
        (new SentenceSeeder)->run();
    }
}
