<?php

namespace Database\Seeders;

use App\Models\Deck;
use App\Models\Term;
use App\Models\User;
use Illuminate\Database\Seeder;
use Maize\Markable\Models\Bookmark;

class DeckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $users = User::all();
        $terms = Term::pluck('id');

        foreach ($users as $user) {
            Deck::factory(3)->create(['user_id' => $user->id])->each(function ($deck) use ($user, $terms) {
                $randomTerms = $terms->random(rand(10, 20))->values();

                $termsWithPosition = [];
                foreach ($randomTerms as $index => $termId) {
                    $termsWithPosition[$termId] = ['position' => $index + 1];
                }

                $deck->terms()->attach($termsWithPosition);

                Bookmark::add($deck, $user);
            });
        }
    }
}
