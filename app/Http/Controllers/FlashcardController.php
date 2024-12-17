<?php

namespace App\Http\Controllers;

use App\Models\Deck;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class FlashcardController extends Controller
{
    public function __construct(protected FlasherInterface $flasher)
    {
    }

    public function index()
    {
        View::share('pageTitle', 'Flashcard Viewer');

        return view('users.flashcards.index', [
            'layout' => 'app'
        ]);
    }

    public function getDecks(Request $request)
    {
//        TODO: What happens if a Deck you've pinned becomes private?

        try {
            $user = $request->user();
            $decks = [];

            $pinnedDecks = Deck::select('decks.*')
                ->join('markable_bookmarks', function ($join) use ($user) {
                    $join->on('decks.id', '=', 'markable_bookmarks.markable_id')
                        ->where('markable_bookmarks.markable_type', '=', Deck::class)
                        ->where('markable_bookmarks.user_id', '=', $user->id);
                })
                ->orderBy('markable_bookmarks.id')
                ->get();

            foreach ($pinnedDecks as $deck) {
                $decks[] = [
                    'id' => $deck->id,
                    'name' => $deck->name,
                    'description' => $deck->description,
                    'count' => count($deck->terms),
                    'authorName' => $deck->author->name,
                    'authorAvatar' => asset('img/avatars/' . $deck->author->avatar),
                    'terms' => $deck->terms->pluck('term'),
//        TODO: disallow empty Decks, maybe passing "disabled" if it's empty

                ];
            }

            return response()->json(['decks' => $decks]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch saved decks.'], 500);
        }
    }

    public function getCards($id)
    {
        $deck = Deck::findOrFail($id);
        $cards = [];

        foreach ($deck->terms as $term) {
            $term->pronunciation = $term->pronunciations->first();
            $pronunciation = $term->pronunciations->firstWhere('dialect_id', auth()->user()->dialect_id);
            $pronunciation && $term->pronunciation = $pronunciation;

            $cards[] = [
                'id' => $term->id,
                'term' => $term->term,
                'category' => $term->category,
                'translit' => $term->pronunciation->translit,
                'file' => $term->pronunciation->audios[0]->filename ?? null,
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
                }),
            ];
        }

        return response()->json([
            'cards' => $cards,
        ]);
    }
}
