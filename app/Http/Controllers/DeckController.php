<?php

namespace App\Http\Controllers;

use App\Events\DeckBuilt;
use App\Events\ModelPinned;
use App\Http\Requests\UpsertDeckRequest;
use App\Http\Resources\DeckResource;
use App\Models\Deck;
use App\Models\Term;
use App\Services\SearchService;
use App\Services\TermService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Maize\Markable\Models\Bookmark;
use Illuminate\Support\Facades\URL;
use Throwable;

class DeckController extends Controller
{
    public function __construct(
        protected TermService $termService
    ) {
    }

    public function pin(Request $request, Deck $deck): JsonResponse
    {
        Gate::authorize('interact', $deck);

        $user = $request->user();
        Bookmark::toggle($deck, $user);

        $isPinned = Bookmark::has($deck, $user);

        if ($isPinned) {
            event(new ModelPinned($user));
        }

        return response()->json([
            'pinCount' => Bookmark::count($deck),
            'isPinned' => $isPinned,
            'modelKey' => $deck->name,
        ]);
    }

    public function index(): \Inertia\Response
    {
        return Inertia::render('Library/Decks/Index');
    }

    public function show(Deck $deck): \Inertia\Response
    {
        return Inertia::render('Library/Decks/Show', [
            'deckId' => $deck->id,
        ]);
    }

    // -------------------------------------------------------------------------
    // API Methods
    // -------------------------------------------------------------------------

    public function apiIndex(Request $request, SearchService $searchService): JsonResponse
    {
        URL::forceScheme('https');

        $filters = array_merge(['sort' => 'latest'], $request->only([
            'search', 'match', 'sort', 'pinned',
        ]));

        $perPage = 25;
        $currentPage = $request->integer('page', 1);

        $decksCollection = $searchService->search($filters, false, true)['decks'];
        $decks = new \Illuminate\Pagination\LengthAwarePaginator(
            $decksCollection->forPage($currentPage, $perPage)->values(),
            $decksCollection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $resource = DeckResource::collection($decks);

        return response()->json([
            'decks' => [
                'data' => $resource->toArray($request),
                'meta' => [
                    'links' => $decks->linkCollection()->toArray(),
                    'current_page' => $decks->currentPage(),
                    'last_page' => $decks->lastPage(),
                    'total' => $decks->total(),
                ],
            ],
            'totalCount' => $decks->total(),
            'filters' => $filters,
        ]);
    }

    public function fetch(Request $request, Deck $deck): JsonResponse
    {
        $includes = collect(explode(',', (string) $request->query('include')))
            ->map(fn (string $include) => trim($include))
            ->filter()
            ->values();

        if ($includes->contains('show') || $includes->isEmpty()) {
            Gate::authorize('interact', $deck);

            $deck->load(['scores']);

        } else {
            if ($includes->contains('edit')) {
                Gate::authorize('modify', $deck);

                $deck->load([
                    'terms' => fn ($q) => $q
                        ->withItemData(),
                ]);

                $this->termService->hydratePronunciations($deck->terms);
            }
        }

        return response()->json([
            'deck' => new DeckResource($deck),
        ]);
    }

    public function getDeckTerms(Deck $deck): JsonResponse
    {
        Gate::authorize('interact', $deck);

        $deck->load([
            'terms' => fn ($q) => $q
                ->whereNotNull('deck_term.gloss_id')
                ->withItemData()
                ->withUserCard(),
        ]);

        $this->termService->hydratePronunciations($deck->terms);

        return response()->json([
            'deck' => new DeckResource($deck),
        ]);
    }

    // -------------------------------------------------------------------------

    public function store(UpsertDeckRequest $request): JsonResponse
    {
        $user = $request->user();

        $deck = DB::transaction(function () use ($request, $user) {
            $deck = Deck::create(array_merge($request->safe()->only([
                'name',
                'description',
                'private',
            ]), ['user_id' => $user->id]));

            $this->linkTerms($deck, $request->input('terms', []));

            return $deck;
        });

        Bookmark::add($deck, $user);
        event(new ModelPinned($user));
        event(new DeckBuilt($user));

        $deck = Deck::query()
            ->whereKey($deck->getKey())
            ->with([
                'terms' => fn ($q) => $q
                    ->whereNotNull('deck_term.gloss_id')
                    ->withItemData(),
            ])
            ->firstOrFail();

        $this->termService->hydratePronunciations($deck->terms);

        return response()->json([
            'deck' => new DeckResource($deck),
        ], 201);
    }

    public function update(UpsertDeckRequest $request, Deck $deck): JsonResponse
    {
        Gate::authorize('modify', $deck);

        DB::transaction(function () use ($request, $deck) {
            $deck->update($request->safe()->only([
                'name',
                'description',
                'private',
            ]));

            $this->linkTerms($deck, $request->input('terms', []));
        });

        $deck = Deck::query()
            ->whereKey($deck->getKey())
            ->with([
                'terms' => fn ($q) => $q
                    ->whereNotNull('deck_term.gloss_id')
                    ->withItemData(),
            ])
            ->firstOrFail();

        $this->termService->hydratePronunciations($deck->terms);

        return response()->json([
            'deck' => new DeckResource($deck),
        ]);
    }

    private function linkTerms($deck, $terms): void
    {
        $now = now();

        $existingRows = DB::table('deck_term')
            ->where('deck_id', $deck->id)
            ->get()
            ->keyBy('id');

        $incomingRows = collect($terms ?? [])
            ->map(fn ($termData) => [
                'id' => $termData['deckPivot']['id'] ?? null,
                'term_id' => $termData['id'],
                'gloss_id' => $termData['deckPivot']['gloss_id'],
                'position' => $termData['deckPivot']['position'],
            ]);

        $incomingExistingRows = $incomingRows
            ->filter(fn ($row) => $row['id'] && $existingRows->has($row['id']))
            ->keyBy('id');

        $rowsToDelete = $existingRows->keys()->diff($incomingExistingRows->keys());

        if ($rowsToDelete->isNotEmpty()) {
            DB::table('deck_term')
                ->where('deck_id', $deck->id)
                ->whereIn('id', $rowsToDelete)
                ->delete();
        }

        $rowsToInsert = $incomingRows
            ->reject(fn ($row) => $row['id'] && $existingRows->has($row['id']))
            ->map(fn ($row) => [
                'deck_id' => $deck->id,
                'term_id' => $row['term_id'],
                'gloss_id' => $row['gloss_id'],
                'position' => $row['position'],
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->all();

        if ($rowsToInsert) {
            DB::table('deck_term')->insert($rowsToInsert);
        }

        foreach ($incomingExistingRows as $row) {
            $existing = $existingRows->get($row['id']);

            if (
                (int) $existing->term_id === (int) $row['term_id']
                && (int) $existing->gloss_id === (int) $row['gloss_id']
                && (int) $existing->position === (int) $row['position']
            ) {
                continue;
            }

            DB::table('deck_term')
                ->where('deck_id', $deck->id)
                ->where('id', $row['id'])
                ->update([
                    'term_id' => $row['term_id'],
                    'gloss_id' => $row['gloss_id'],
                    'position' => $row['position'],
                    'updated_at' => $now,
                ]);
        }

        $deck->unsetRelation('terms');
    }

    public function destroy(Deck $deck): JsonResponse
    {
        try {
            Gate::authorize('delete', $deck);

            DB::transaction(function () use ($deck) {
                $deck->delete();
            });

            return response()->json([
                'success' => true,
            ]);

        } catch (Throwable $e) {
            Log::error('Failed to delete Deck.', [
                'deck_id' => $deck->id,
                'exception' => $e,
            ]);

            return $this->failureJsonResponse('Unable to delete Deck.', $e);
        }
    }

    public function toggleTerm(Deck $deck, Term $term): JsonResponse
    {
        Gate::authorize('modify', $deck);

        if (! $deck->terms->contains($term->id)) {
            $position = $deck->terms->count() + 1;
            $deck->terms()->attach($term->id, [
                'position' => $position,
                'gloss_id' => $term->glosses->first()->id,
            ]);

        } else {
            $deck->terms()->detach($term->id);

            $terms = $deck->terms()
                ->orderBy('pivot_position')
                ->get();

            $newPosition = 1;
            foreach ($terms as $t) {
                $deck->terms()->updateExistingPivot($t->id, [
                    'position' => $newPosition,
                ]);
                $newPosition++;
            }
        }

        $deck->load('terms');
        $isPresent = $deck->terms->contains($term->id);

        return response()->json([
            'isPresent' => $isPresent,
        ]);
    }

    public function copy(Request $request, Deck $deck): JsonResponse
    {
        Gate::authorize('interact', $deck);

        $user = $request->user();

        $newDeck = $deck->replicate(['id', 'private', 'terms_count', 'is_pinned']);

        $newDeck->private = 0;
        $newDeck->user_id = $user->id;
        $newDeck->description = "My copy of {$deck->author->name} ({$deck->author->username})'s {$deck->name} Deck.";

        $newDeck->name .= ' (Copy)';
        $newDeck->save();

        Bookmark::add($deck, $user);

        $deckTerms = $deck->terms()
            ->whereNotNull('deck_term.gloss_id')
            ->get();

        foreach ($deckTerms as $index => $term) {
            $newDeck->terms()->attach($term->id, [
                'gloss_id' => $term->pivot->gloss_id,
                'position' => $index + 1,
            ]);
        }

        return response()->json([
            'success' => true,
            'deckId' => $newDeck->id,
        ]);
    }

    public function export(Deck $deck): never
    {
        Gate::authorize('interact', $deck);

        $deck->load([
            'terms' => fn ($q) => $q->whereNotNull('deck_term.gloss_id'),
            'terms.glosses',
        ]);

        foreach ($deck->terms as $term) {
            $glosses = $term->glosses->pluck('gloss')->implode('; ');

            $data[] = [
                $term->term.' ('.$term->translit.')',
                $glosses,
            ];
        }

        $output = fopen('php://output', 'w');
        if (! $output) {
            throw new \Exception('Failed to open php://output');
        }

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="'.$deck->name.'"');

        fputcsv($output, array_shift($data));

        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }

    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'lesson_id' => ['nullable', 'integer', 'exists:lessons,id'],
        ]);

        $q = trim($validated['q'] ?? '');
        $lessonId = $validated['lesson_id'] ?? null;

        $decks = Deck::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('decks.name', 'like', $q.'%');
            })
            ->whereNotExists(function ($sub) use ($lessonId) {
                $sub->select(DB::raw(1))
                    ->from('lessons')
                    ->whereColumn('lessons.deck_id', 'decks.id');

                if ($lessonId) {
                    $sub->where('lessons.id', '!=', $lessonId);
                }
            })
            ->orderBy('decks.name')
            ->limit(10)
            ->get();

        return response()->json([
            'results' => DeckResource::collection($decks),
        ]);
    }
}
