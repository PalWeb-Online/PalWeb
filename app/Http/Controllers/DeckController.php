<?php

namespace App\Http\Controllers;

use App\Events\DeckBuilt;
use App\Events\ModelPinned;
use App\Http\Requests\StoreDeckRequest;
use App\Http\Requests\UpdateDeckRequest;
use App\Http\Resources\DeckResource;
use App\Models\Deck;
use App\Models\Term;
use App\Services\SearchService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maize\Markable\Models\Bookmark;

class DeckController extends Controller
{
    public function pin(Request $request, Deck $deck): JsonResponse
    {
        $this->authorize('interact', $deck);

        $user = $request->user();

        Bookmark::toggle($deck, $user);

        $deck->isPinned() && event(new ModelPinned($user));

        $message = $deck->isPinned() ? __('pin.added', ['thing' => $deck->name]) : __('pin.removed', ['thing' => $deck->name]);
        session()->flash('notification', ['type' => 'success', 'message' => $message]);

        return response()->json([
            'pinCount' => Bookmark::count($deck),
            'isPinned' => $deck->isPinned(),
        ]);
    }

    public function index(Request $request, SearchService $searchService): \Inertia\Response
    {
        $filters = $request->only(['search', 'match', 'pinned']);

        if (! collect($filters)->some(fn ($value) => ! empty($value))) {
            $decks = Deck::query()
                ->orderByDesc('id')
                ->paginate(25)
                ->onEachSide(1)
                ->appends($filters);
            $totalCount = $decks->total();

        } else {
            $results = $searchService->search($filters, false, true)['decks'];
            $totalCount = $results->count();

            $perPage = 25;
            $currentPage = $request->input('page', 1);
            $decks = $results->forPage($currentPage, $perPage);

            $decks = new \Illuminate\Pagination\LengthAwarePaginator(
                $decks,
                $totalCount,
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        }

        return Inertia::render('Library/Decks/Index', [
            'section' => 'library',
            'decks' => DeckResource::collection($decks),
            'totalCount' => $totalCount,
            'filters' => $filters,
        ]);
    }

    public function show(Deck $deck): \Inertia\Response
    {
        $this->authorize('interact', $deck);

        $deck->load(['terms.pronunciations']);

        return Inertia::render('Library/Decks/Show', [
            'section' => 'library',
            'deck' => new DeckResource($deck)
        ]);
    }

    public function store(StoreDeckRequest $request): RedirectResponse
    {
        $user = $request->user();

        $deck = Deck::create(array_merge($request->all(), ['user_id' => $user->id]));
        $this->linkTerms($deck, $request->terms);

        Bookmark::add($deck, $user);
        event(new ModelPinned($user));
        event(new DeckBuilt($user));

        return to_route('decks.show', $deck);
    }

    public function update(UpdateDeckRequest $request, Deck $deck): RedirectResponse
    {
        $this->authorize('modify', $deck);

        $deck->update($request->all());
        $this->linkTerms($deck, $request->terms);

        return to_route('decks.show', $deck);
    }

    private function linkTerms($deck, $terms): void
    {
        foreach ($terms as $termData) {
            $term = Term::find($termData['id']);

            if ($term) {
                $deck->terms()->syncWithoutDetaching([
                    $term->id => [
                        'gloss_id' => $termData['deckPivot']['gloss_id'],
                        'position' => $termData['deckPivot']['position'],
                    ],
                ]);
            }
        }

        foreach ($deck->terms as $term) {
            if (! in_array($term->id, array_column($terms, 'id'))) {
                $deck->terms()->detach($term->id);
            }
        }
    }

    public function destroy(Deck $deck): RedirectResponse
    {
        $this->authorize('modify', $deck);

        $deck->delete();
        session()->flash('notification',
            ['type' => 'success', 'message' => __('deleted', ['thing' => $deck->name])]);
        return to_route('decks.index');
    }

    public function toggleTerm(Deck $deck, Term $term): JsonResponse
    {
        $this->authorize('modify', $deck);

        if (! $deck->terms->contains($term->id)) {
            $position = $deck->terms->count() + 1;
            $deck->terms()->attach($term->id, ['position' => $position]);

        } else {
            $deck->terms()->detach($term->id);
        }

        return response()->json([
            'isPresent' => ! $deck->terms->contains($term->id),
            'message' => ! $deck->terms->contains($term->id)
                ? __('decks.term.added', ['term' => $term->term, 'deck' => $deck->name])
                : __('decks.term.removed', ['term' => $term->term, 'deck' => $deck->name]),
        ]);
    }

    public function copy(Request $request, Deck $deck): RedirectResponse
    {
        $this->authorize('interact', $deck);

        $user = $request->user();

        $newDeck = $deck->replicate(['id', 'private', 'terms_count']);

        $newDeck->private = 0;
        $newDeck->user_id = $user->id;
        $newDeck->description = "My copy of {$deck->author->name} ({$deck->author->username})'s {$deck->name} Deck.";

        $newDeck->name .= ' (Copy)';
        $newDeck->save();

        Bookmark::add($deck, $user);

        $termIds = $deck->terms()->pluck('term_id');

        foreach ($termIds as $index => $id) {
            $newDeck->terms()->attach($id, ['position' => $index + 1]);
        }

        session()->flash('notification',
            ['type' => 'success', 'message' => __('deck.copied', ['deck' => $deck->name])]);
        return to_route('decks.show', $newDeck->id);
    }

    public function export(Deck $deck): never
    {
        $this->authorize('interact', $deck);

        $deck->load(['terms.glosses']);

        foreach ($deck->terms as $term) {
            $glosses = $term->glosses->pluck('gloss')->implode('; ');

            $data[] = [
                $term->term.' ('.$term->translit.')',
                $glosses,
            ];
        }

        $output = fopen('php://output', 'w');
        if (! $output) {
            throw new Exception('Failed to open php://output');
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
}
