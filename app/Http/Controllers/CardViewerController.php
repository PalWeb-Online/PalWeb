<?php

namespace App\Http\Controllers;

use App\Models\Deck;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CardViewerController extends Controller
{
    public function __construct(protected FlasherInterface $flasher) {}

    public function index(): \Illuminate\View\View
    {
        View::share('pageTitle', 'Card Viewer');

        return view('decks.viewer', [
            'layout' => 'app',
        ]);
    }

    public function getPinnedDecks(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $pinnedDecks = [];

            $decks = Deck::with('author')
                ->select('decks.*')
                ->where(fn ($query) => $query->where('decks.private', false)
                    ->orWhere('decks.user_id', $user->id)
                )
                ->join('markable_bookmarks', fn ($join) => $join->on('decks.id', '=', 'markable_bookmarks.markable_id')
                    ->where('markable_bookmarks.markable_type', '=', Deck::class)
                    ->where('markable_bookmarks.user_id', '=', $user->id)
                )
                ->orderByDesc('markable_bookmarks.id')
                ->get();

            foreach ($decks as $deck) {
                $pinnedDecks[] = [
                    'id' => $deck->id,
                    'name' => $deck->name,
                    'description' => $deck->description,
                    'terms' => $deck->terms->pluck('term'),
                    'count' => count($deck->terms),
                    'authorName' => $deck->author->name,
                    'authorAvatar' => asset('img/avatars/'.$deck->author->avatar),
                    'isPinned' => $deck->isPinned(),
                ];
            }

            return response()->json(['pinnedDecks' => $pinnedDecks]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch Pinned Decks.'], 500);
        }
    }

    public function getCards($id)
    {
        $terms = [];

        foreach (Deck::findOrFail($id)->terms as $term) {
            $term->pronunciation = $term->pronunciations->first();
            $pronunciation = $term->pronunciations->firstWhere('dialect_id', auth()->user()->dialect_id);
            $pronunciation && $term->pronunciation = $pronunciation;

            $terms[] = [
                'id' => $term->id,
                'term' => $term->term,
                'category' => $term->category,
                'translit' => $term->pronunciation->translit,
                'file' => $term->pronunciation->audios[0]->filename ?? null,
                'inflections' => $term->inflections->whereNotIn('form', ['accusative', 'genitive'])
                    ->map(function ($inflection) {
                        return [
                            'inflection' => $inflection->inflection,
                            'translit' => $inflection->translit,
                        ];
                    }),
                'glosses' => $term->glosses
                    ->map(function ($gloss) {
                        return [
                            'id' => $gloss->id,
                            'gloss' => $gloss->gloss,
                        ];
                    }),
            ];
        }

        return response()->json([
            'terms' => $terms,
        ]);
    }
}
