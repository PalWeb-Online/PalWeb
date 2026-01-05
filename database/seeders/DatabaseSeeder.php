<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        (new BadgeSeeder)->run();
        (new DialectSeeder)->run();
        (new LocationSeeder)->run();
        (new PatternSeeder)->run();
        (new AttributeSeeder)->run();
        (new UserSeeder)->run();
        (new SpeakerSeeder)->run();
        (new RoleSeeder)->run();

        (new TermSeeder)->run();
        (new DeckSeeder)->run();

        (new AudioSeeder)->run();

        (new SentenceSeeder)->run();
        (new DialogSeeder)->run();

        (new UnitSeeder)->run();
        (new ScoreSeeder)->run();
    }
}
