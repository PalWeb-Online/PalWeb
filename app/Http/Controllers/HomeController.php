<?php

namespace App\Http\Controllers;

use App\Http\Resources\DeckResource;
use App\Http\Resources\SentenceResource;
use App\Http\Resources\TermResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserShowResource;
use App\Models\Audio;
use App\Models\Deck;
use App\Models\Dialog;
use App\Models\Sentence;
use App\Models\Term;
use App\Models\User;
use App\Services\TermService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Home');
    }

    public function apiIndex(TermService $termService): JsonResponse
    {
        $counts = Cache::remember('public_model_counts', now()->addHour(), function () {
            return [
                'terms' => Term::count(),
                'sentences' => Sentence::count(),
                'users' => User::count(),
                'decks' => Deck::where('private', false)->count(),
                'dialogs' => Dialog::count(),
                'audios' => Audio::count(),
            ];
        });

        $isStaging = app()->environment('staging');

        $users = ! $isStaging
            ? User::whereKey([7, 10, 11, 18, 19, 878, 1113, 1115, 1186, 1224])->get()
            : User::inRandomOrder()->limit(10)->get();
        $users->load('selectedAvatar');

        $decks = ! $isStaging
            ? Deck::with('terms')->whereKey([2, 3, 4, 12, 19, 83, 100, 118])->get()
            : Deck::with('terms')
                ->where('private', false)
                ->inRandomOrder()
                ->limit(8)
                ->get();

        $sentences = ! $isStaging
            ? Sentence::whereKey([256, 66, 54])->get()
            : Sentence::inRandomOrder()->limit(3)->get();

        $testimonialComments = [
            'PalWeb has made it so much easier to connect with real spoken Arabic. The dictionary and example sentences help me sound natural, not just textbook-correct.',
            'Finally - a resource that respects the richness of Palestinian Arabic and makes it accessible to learners. My students love the interactive decks and real-life examples.',
            'Recording audio for PalWeb has been a powerful way to share my dialect and support learners around the world. It\'s exciting to be part of something that preserves our language.',
            'PalWeb stands out as a resource because of its content & the structuring of vocabulary on the site, where you can break down sentences into their constituent words and even words into their dictionary form. This is a format that more language sites should seek to emulate.',
            'I\'m learning Palestinian Arabic to connect better with my family, and PalWeb has been a lifesaver. I love that I can hear everything spoken out loud!',
        ];

        $testimonialUsers = ! $isStaging
            ? User::whereKey([243, 1317, 16, 18, 3])->get()
            : User::inRandomOrder()->limit(count($testimonialComments))->get();
        $testimonialUsers->load('selectedAvatar');

        $testimonials = collect($testimonialComments)
            ->map(fn (string $comment, int $index) => [
                'user' => new UserResource(
                    $testimonialUsers->values()->get($index) ?? User::first()
                ),
                'comment' => $comment,
            ])
            ->all();

        $termCollection = ! $isStaging
            ? Term::whereKey(662)
                ->withItemData()
                ->with(['inflections'])
                ->get()
            : Term::query()
                ->withItemData()
                ->with(['inflections'])
                ->limit(1)
                ->get();

        if ($termCollection->isNotEmpty()) {
            $termService->hydratePronunciations($termCollection);
            $featuredTerm = new TermResource($termCollection->first());
        }

        return response()->json([
            'count' => $counts,
            'users' => UserResource::collection($users),
            'decks' => DeckResource::collection($decks),
            'sentences' => SentenceResource::collection($sentences),
            'testimonials' => $testimonials,
            'featuredTerm' => $termCollection->isNotEmpty() ? $featuredTerm : null,
            'featuredUser' => User::find(1) ? new UserShowResource(User::find(1)->load([
                'selectedAvatar', 'dialect',
            ])) : null,
            'featuredDeck' => Deck::find(2) ? new DeckResource(Deck::find(2)->load([
                'terms' => fn ($q) => $q->whereNotNull('deck_term.gloss_id'),
            ])) : null,
        ]);
    }
}
