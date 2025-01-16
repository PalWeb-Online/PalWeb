<?php

namespace Database\Seeders;

use App\Models\Sentence;
use App\Models\Term;
use Illuminate\Database\Seeder;

class SentenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $terms = Term::all();

        Sentence::factory(50)->create()->each(function ($sentence) use ($terms) {
            $randomTerms = $terms->random(rand(3, 8))->values();

            $termsToAttach = [];
            foreach ($randomTerms as $index => $term) {
                $termsToAttach[$term->id] = [
                    'gloss_id' => $term->glosses[0]->id,
                    'sent_term' => $term->term,
                    'sent_translit' => $term->translit,
                    'position' => $index + 1,
                ];
            }

            $sentence->terms()->attach($termsToAttach);

            $sentenceTerms = [];
            $sentenceTranslits = [];

            foreach ($sentence->terms as $term) {
                $sentenceTerms[] = $term->pivot->sent_term;
                $sentenceTranslits[] = $term->pivot->sent_translit;
            }

            $sentence->sentence = implode(' ', $sentenceTerms);
            $sentence->translit = implode(' ', $sentenceTranslits);
            $sentence->save();
        });
    }
}
