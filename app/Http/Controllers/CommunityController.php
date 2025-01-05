<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use App\Models\Deck;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class CommunityController extends Controller
{
    public function index(Request $request)
    {
        $latestDecks = Deck::with('author')
            ->where('private', false)
            ->orderByDesc('id')
            ->take(5)
            ->get();

        $popularDecks = Deck::with('author')
            ->where('private', false)
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
        if (!$featuredDeck) {
            $featuredDeck = Deck::inRandomOrder()->first();
        }

        $latestAudios = Audio::orderByDesc('id')->take(8)->get();

        $users = User::where('users.private', false)
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

        View::share('pageTitle', 'Community');

        return view('community.index', [
            'latestDecks' => $latestDecks,
            'popularDecks' => $popularDecks,
            'featuredDeck' => $featuredDeck,
            'latestAudios' => $latestAudios,
            'users' => $users,
        ]);
    }
}
