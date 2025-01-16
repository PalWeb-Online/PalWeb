<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BadgeSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        // AfterEmailVerified
        DB::table('badges')->insert([
            'name' => 'I\'m Just Happy to Be Here',
            'description' => 'Verified your email.',
            'image' => 'ImJustHappyToBeHere.svg',
        ]);

        // ProfileChanged & AwardProfileChangedBadge
        DB::table('badges')->insert([
            'name' => 'We\'re Happy to Have You',
            'description' => 'Told us about yourself.',
            'image' => 'WereHappyToHaveYou.svg',
        ]);

        // awarded by bot
        DB::table('badges')->insert([
            'name' => 'No FOMO',
            'description' => 'Joined the Discord server.',
            'image' => 'NoFOMO.svg',
        ]);

        // DonatedMoney & AwardDonatedMoneyBadge
        DB::table('badges')->insert([
            'name' => 'Pay It Forward',
            'description' => 'Bought us a coffee.',
            'image' => 'PayItForward.svg',
        ]);

        // TermBookmarked & AwardTermBookmarkedBadge
        DB::table('badges')->insert([
            'name' => 'Baby\'s First Words',
            'description' => 'Pinned 10 Terms to Workbench.',
            'image' => 'BabysFirstWords.svg',
        ]);

        // SentenceBookmarked & AwardSentenceBookmarkedBadge
        DB::table('badges')->insert([
            'name' => 'Loquacious',
            'description' => 'Pinned 5 Sentences to Workbench.',
            'image' => 'Loquacious.svg',
        ]);

        // DeckCreated & AwardDeckCreatedBadge
        DB::table('badges')->insert([
            'name' => 'Word Collector',
            'description' => 'Built a Deck.',
            'image' => 'WordCollector.svg',
        ]);

        // DeckSaved & AwardDeckSavedBadge
        DB::table('badges')->insert([
            'name' => 'Mine!',
            'description' => 'Pinned 5 Decks to Workbench.',
            'image' => 'Mine.svg',
        ]);
    }
}
