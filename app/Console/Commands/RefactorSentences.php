<?php

namespace App\Console\Commands;

use App\Models\Sentence;
use App\Models\Term;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RefactorSentences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refactor:sentences';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refactors Sentences.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $sentences = Sentence::all();

        foreach ($sentences as $sentence) {

            $terms = explode(',', $sentence->sentence);

            $refactoredSentence = [];

            foreach ($terms as $i => $term) {
                $term = trim($term, '/');
                $term = explode('/', $term);

                $refactoredSentence[] = $term[2];

                $foundTerm = Term::firstWhere('slug', $term[0]);

                if ($foundTerm) {
                    $sentence->terms()->attach($foundTerm, [
                        'gloss_id' => $foundTerm->glosses[0]->id,
                        'sent_term' => $term[2],
                        'sent_translit' => $term[1],
                        'position' => $i + 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                } else {
                    DB::table('sentence_term')->insert([
                        'sentence_id' => $sentence->id,
                        'term_id' => null,
                        'gloss_id' => null,
                        'sent_term' => $term[2],
                        'sent_translit' => $term[1],
                        'position' => $i + 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            $sentence->sentence = implode(' ', $refactoredSentence);
            $sentence->save();
        }

        return Command::SUCCESS;
    }
}
