<?php

namespace App\Http\Controllers;

use App\Http\Resources\AudioResource;
use App\Http\Resources\DeckResource;
use App\Http\Resources\UserResource;
use App\Models\Audio;
use App\Models\Deck;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CommunityController extends Controller
{
    public function index(): \Inertia\Response
    {
        $latestDecks = Deck::query()
            ->orderByDesc('id')
            ->take(5)
            ->get();

        $popularDecks = Deck::query()
            ->join('markable_bookmarks', function ($join) {
                $join->on('decks.id', '=', 'markable_bookmarks.markable_id')
                    ->where('markable_bookmarks.markable_type', Deck::class);
            })
            ->select('decks.*', DB::raw('COUNT(markable_bookmarks.user_id) as users_count'))
            ->groupBy('decks.id')
            ->havingRaw('COUNT(markable_bookmarks.user_id) > 1')
            ->orderByDesc('users_count')
            ->take(5)
            ->get();

        $featuredDeck = Cache::get('featured-deck');
        if (! $featuredDeck) {
            $featuredDeck = Deck::inRandomOrder()->with(['terms'])->first();
        }

        $latestAudios = Audio::query()
            ->with([
                'speaker',
                'pronunciation.term',
            ])
            ->orderByDesc('id')
            ->take(10)
            ->get();

        $topUsers = User::where('users.private', false)
            ->whereNotIn('users.id', [1, 2])
            ->leftJoin('decks', function ($join) {
                $join->on('decks.user_id', 'users.id')->where('decks.private', false);
            })
            ->leftJoin('speakers', 'speakers.user_id', '=', 'users.id')
            ->leftJoin('audios', 'audios.speaker_id', '=', 'speakers.id')
            ->selectRaw('users.*, (COUNT(decks.id) * 5) + COUNT(audios.id) as weighted_score')
            ->groupBy('users.id')
            ->orderByDesc('weighted_score')
            ->take(5)
            ->get();

        return Inertia::render('Community/Users/Index', [
            'section' => 'community',
            'latestDecks' => DeckResource::collection($latestDecks),
            'popularDecks' => DeckResource::collection($popularDecks),
            'featuredDeck' => new DeckResource($featuredDeck),
            'latestAudios' => AudioResource::collection($latestAudios),
            'topUsers' => UserResource::collection($topUsers),
        ]);
    }
}
