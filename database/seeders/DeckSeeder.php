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
     */
    public function run(): void
    {
        $users = User::all();
        $terms = Term::all();

        foreach ($users as $user) {
            Deck::factory(3)->create(['user_id' => $user->id])->each(function ($deck) use ($user, $terms) {
                $randomTerms = $terms->random(rand(10, 20))->values();

                $termsWithPivot = [];
                foreach ($randomTerms as $index => $term) {
                    $termsWithPivot[$term->id] = [
                        'position' => $index + 1,
                        'gloss_id' => $term->glosses->first()->id,
                    ];
                }

                $deck->terms()->attach($termsWithPivot);

                Bookmark::add($deck, $user);
            });
        }
    }
}
