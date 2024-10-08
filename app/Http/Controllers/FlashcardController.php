<?php

namespace App\Http\Controllers;

use App\Models\Deck;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\View;

class FlashcardController extends Controller
{
    public function __construct(protected FlasherInterface $flasher)
    {
    }

    public function study(Deck $deck)
    {
        if ($deck->terms->isNotEmpty()) {
            View::share('pageTitle', 'Flashcard Portal');
            return view('users.flashcards.study', [
                'deck' => $deck,
                'layout' => 'app',
            ]);

        } else {
            $this->flasher->addFlash('error', 'Can\'t study an empty Deck.');
            return back();
        }
    }

    public function get($id)
    {
        $deck = Deck::findOrFail($id);

        $terms = [];
        foreach ($deck->terms as $term) {
            $term->pronunciation = $term->pronunciations->first();
            $pronunciation = $term->pronunciations->firstWhere('dialect_id', auth()->user()->dialect_id);
            $pronunciation && $term->pronunciation = $pronunciation;

            $terms[] = [
                'id' => $term->id,
                'term' => $term->term,
                'category' => $term->category,
                'translit' => $term->pronunciation->translit,
                'file' => $term->pronunciation->audify(),
                'inflections' => $term->inflections->whereNotIn('form', ['accusative', 'genitive'])->map(function ($inflection) {
                    return [
                        'inflection' => $inflection->inflection,
                        'translit' => $inflection->translit,
                    ];
                }),
                'glosses' => $term->glosses->map(function ($gloss) {
                    return [
                        'id' => $gloss->id,
                        'gloss' => $gloss->gloss,
                    ];
                })->toArray(),
                'routes' => [
                    'view' => route('terms.show', $term),
                    'edit' => route('terms.edit', $term),
                    'delete' => route('terms.destroy', $term),
                    'usages' => route('terms.usages', $term),
                ],
            ];
        }

        $deck = [
            'name' => $deck->name,
            'view' => route('decks.show', $deck),
        ];

        return response()->json([
            'deck' => $deck,
            'terms' => $terms,
            'imageURL' => asset('/img'),
            'isUser' => true,
            'isAdmin' => auth()->user()->isAdmin(),
        ]);
    }
}
