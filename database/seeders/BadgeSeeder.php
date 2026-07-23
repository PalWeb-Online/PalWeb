<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BadgeSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // AfterEmailVerified
        DB::table('badges')->insert([
            'title' => 'I\'m Just Happy to Be Here',
            'description' => 'Verified your email.',
            'key' => 'user_verified',
        ]);

        // ProfileChanged & AwardProfileChangedBadge
        DB::table('badges')->insert([
            'title' => 'We\'re Happy to Have You',
            'description' => 'Told us about yourself.',
            'key' => 'user_profile_updated',
        ]);

        // awarded by bot
        DB::table('badges')->insert([
            'title' => 'No FOMO',
            'description' => 'Joined the Discord server.',
            'key' => 'joined_discord',
        ]);

        // DonatedMoney & AwardDonatedMoneyBadge
        DB::table('badges')->insert([
            'title' => 'Pay It Forward',
            'description' => 'Bought us a coffee.',
            'key' => 'user_subscribed',
        ]);

        // TermBookmarked & AwardTermBookmarkedBadge
        DB::table('badges')->insert([
            'title' => 'Baby\'s First Words',
            'description' => 'Pinned 10 Terms to Workbench.',
            'key' => 'pinned_terms',
        ]);

        // SentenceBookmarked & AwardSentenceBookmarkedBadge
        DB::table('badges')->insert([
            'title' => 'Loquacious',
            'description' => 'Pinned 5 Sentences to Workbench.',
            'key' => 'pinned_sentences',
        ]);

        // DeckSaved & AwardDeckSavedBadge
        DB::table('badges')->insert([
            'title' => 'Mine!',
            'description' => 'Pinned 5 Decks to Workbench.',
            'key' => 'pinned_decks',
        ]);

        // DeckCreated & AwardDeckCreatedBadge
        DB::table('badges')->insert([
            'title' => 'Word Collector',
            'description' => 'Built a Deck.',
            'key' => 'created_deck',
        ]);
    }
}
