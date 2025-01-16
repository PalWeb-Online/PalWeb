<?php

namespace App\Http\Controllers;

use App\Models\Deck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class DeckBuilderController extends Controller
{
    public function index()
    {
        View::share('pageTitle', 'Deck Builder');

        return view('decks.builder', [
            'layout' => 'app',
        ]);
    }

    public function edit(Request $request, $deckId)
    {
        $user = $request->user();
        $user = [
            'name' => $user->name,
            'username' => $user->username,
            'avatar' => asset('img/avatars/'.$user->avatar),
        ];

        $deck = Deck::with('terms')->findOrFail($deckId);

        View::share('pageTitle', 'Deck Builder');

        return view('decks.builder', [
            'layout' => 'app',
            'user' => $user,
            'deck' => $deck,
            'action' => 'edit',
        ]);
    }

    public function getCreatedDecks(Request $request)
    {
        try {
            $user = $request->user();
            $createdDecks = [];

            foreach ($user->decks->sortDesc() as $deck) {
                $createdDecks[] = [
                    'id' => $deck->id,
                    'name' => $deck->name,
                    'description' => $deck->description,
                    'terms' => $deck->terms->pluck('term'),
                    'count' => count($deck->terms),
                    'private' => $deck->private,
                    'authorName' => $user->name,
                    'authorAvatar' => asset('img/avatars/'.$user->avatar),
                    'isPinned' => $deck->isPinned(),
                ];
            }

            $user = [
                'name' => $user->name,
                'username' => $user->username,
                'avatar' => asset('img/avatars/'.$user->avatar),
            ];

            return response()->json([
                'user' => $user,
                'createdDecks' => $createdDecks,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch created Decks.'], 500);
        }
    }

    public function getTerms($id)
    {
        $terms = [];
        foreach (Deck::findOrFail($id)->terms as $term) {
            $terms[] = [
                'id' => $term->id,
                'term' => $term->term,
                'category' => $term->category,
                'translit' => $term->translit,
                'glosses' => $term->glosses->map(function ($gloss) {
                    return [
                        'id' => $gloss->id,
                        'gloss' => $gloss->gloss,
                    ];
                })->toArray(),
                'gloss_id' => $term->pivot->gloss_id,
                'position' => $term->pivot->position,
            ];
        }

        return response()->json([
            'terms' => $terms,
        ]);
    }
}
