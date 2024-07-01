<?php

namespace App\Http\Controllers;

use App\Events\DeckBuilt;
use App\Events\ModelPinned;
use App\Models\Deck;
use App\Models\Term;
use Exception;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Maize\Markable\Models\Bookmark;

class DeckController extends Controller
{
    public function __construct(protected FlasherInterface $flasher)
    {
    }

    public function pin(Deck $deck)
    {
        $this->authorize('interact', $deck);

        $user = auth()->user();

        Bookmark::toggle($deck, $user);

        if (Bookmark::has($deck, $user)) {
            event(new ModelPinned($user));
            $this->flasher->addSuccess(__('pin.added', ['thing' => $deck->name]));
        } else {
            $this->flasher->addSuccess(__('pin.removed', ['thing' => $deck->name]));
        }

        return back();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Deck::query();

        if ($searchTerm = $request->search) {
            $query->where('name', 'LIKE', '%'.$searchTerm.'%');
        }

        $decks = $query->with('author')
            ->where('private', 0)
            ->orderByDesc('created_at')
            ->paginate(25)
            ->onEachSide(1);
        $totalCount = $decks->total();

        $newest = Deck::with('author')
            ->where('private', 0)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $mostSaved = Deck::where('private', 0)
            ->join('markable_bookmarks', function ($join) {
                $join->on('decks.id', '=', 'markable_bookmarks.markable_id')
                    ->where('markable_bookmarks.markable_type', Deck::class);
            })
            ->select('decks.*', DB::raw('COUNT(markable_bookmarks.user_id) as users_count'))
            ->groupBy('decks.id')
            ->havingRaw('COUNT(markable_bookmarks.user_id) > 1')
            ->orderByDesc('users_count')
            ->with('author')  // Load author in same query to reduce SQL queries
            ->take(5)
            ->get();

        $featuredDeck = Cache::get('featured-deck');
        if (!$featuredDeck) {
            $featuredDeck = Deck::inRandomOrder()->first();
        }

        View::share('pageTitle', 'Deck Library');

        return view('decks.index', [
            'decks' => $decks,
            'newest' => $newest,
            'mostSaved' => $mostSaved,
            'totalCount' => $totalCount,
            'featuredDeck' => $featuredDeck,
        ]);
    }

    public function get($id)
    {
        $deck = Deck::with('terms')->findOrFail($id);

        $terms = [];
        foreach ($deck->terms as $term) {
            $terms[] = ['slug' => $term->slug, 'position' => $term->pivot->position];
        }

        return response()->json([
            'deck' => $deck,
            'terms' => $terms
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function store(Request $request)
    {
        $this->validateRequest($request);

        $user = auth()->user();

        $deck = $request->deck;
        $deck = array_merge($deck, [
            'user_id' => $user->id,
        ]);
        $deck = Deck::create($deck);
        $this->linkTerms($deck, $request->terms);

        Bookmark::add($deck, $user);
        event(new ModelPinned($user));
        event(new DeckBuilt($user));

        return [
            'status' => 'success',
            'redirect' => route('decks.show', $deck),
            'flash' => __('created', ['thing' => $deck->name])
        ];
    }

    private function validateRequest($request)
    {
        return $request->validate([
            'deck.name' => ['required', 'max:50'],
            'deck.description' => ['nullable', 'max:500'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        View::share('pageTitle', 'Build Deck');
        return view('decks.create');
    }

    private function linkTerms($deck, $terms)
    {
        foreach ($terms as $termData) {
            $term = Term::firstWhere('slug', $termData['slug']);
            if ($term) {
                $deck->terms()->syncWithoutDetaching([$term->id => ['position' => $termData['position']]]);
            }
        }

        foreach ($deck->terms as $term) {
            if (!in_array($term->slug, array_column($terms, 'slug'))) {
                $deck->terms()->detach($term->id);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Deck  $deck
     * @return \Illuminate\Http\Response
     */
    public function show(Deck $deck)
    {
        $this->authorize('interact', $deck);

        $deck->load([
            'author',
            'terms' => function ($query) {
                $query->orderBy('deck_term.id');
            }
        ]);

        View::share('pageTitle', 'Deck: '.$deck->name);
        return view('decks.show', ['deck' => $deck]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Deck  $deck
     * @return \Illuminate\Http\Response
     */
    public function edit(Deck $deck)
    {
        if (Gate::denies('modify', $deck)) {
            $this->flasher->addFlash('error', __('unauthorized.foreign.deck'), __('unauthorized'));
            return back();
        }

        View::share('pageTitle', 'Edit Deck');
        return view('decks.edit', [
            'deck' => $deck
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Deck  $deck
     * @return array
     */
    public function update(Request $request, Deck $deck)
    {
        $this->authorize('modify', $deck);

        $this->validateRequest($request);

        $deck->update($request->deck);
        $this->linkTerms($deck, $request->terms);

        return [
            'status' => 'success',
            'redirect' => route('decks.show', $deck),
            'flash' => __('updated', ['thing' => $deck->name])
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Deck  $deck
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deck $deck)
    {
        $this->authorize('modify', $deck);

        $deck->delete();

        $this->flasher->addSuccess(__('deleted', ['thing' => $deck->name]));
        return to_route('decks.index');
    }

    public function togglePrivacy(Deck $deck)
    {
        $this->authorize('modify', $deck);

        $deck->private = !$deck->private;
        $deck->private ? $status = 'private' : $status = 'public';
        $deck->save();

        $this->flasher->addSuccess(__('privacy.updated', ['status' => $status]));
        return back();
    }

    public function toggleTerm(Deck $deck, Term $term)
    {
        $this->authorize('modify', $deck);

        if (!$deck->terms->contains($term->id)) {
            $position = $deck->terms->count() + 1;
            $deck->terms()->attach($term->id, ['position' => $position]);
            $this->flasher->addSuccess(__('decks.term.added', ['term' => $term->term, 'deck' => $deck->name]));

        } else {
            $deck->terms()->detach($term->id);
            $this->flasher->addSuccess(__('decks.term.removed', ['term' => $term->term, 'deck' => $deck->name]));
        }

        return back();
    }

    public function copy(Deck $deck)
    {
        $this->authorize('interact', $deck);

        $user = auth()->user();

        $newDeck = $deck->replicate(['id', 'private']);
        $newDeck->private = 0;
        $newDeck->user_id = $user->id;
        $newDeck->description = "My copy of {$deck->author->name} ({$deck->author->username})'s {$deck->name} Deck.";

        $newDeck->name .= " (Copy)";
        $newDeck->save();

        $user->decks()->attach($newDeck->id);

        $termIds = $deck->terms()->pluck('term_id');
        $newDeck->terms()->attach($termIds);

        $this->flasher->addSuccess(__('deck.copied', ['deck' => $deck->name]));
        return to_route('decks.show', $newDeck->id);
    }

    public function export(Deck $deck)
    {
        $this->authorize('interact', $deck);

        $deck->load('terms.glosses');

        foreach ($deck->terms as $term) {
            $glosses = $term->glosses->pluck('gloss')->implode('; ');

            $data[] = [
                $term->term.' ('.$term->translit.')',
                $glosses
            ];
        }

        return $this->convertToCsv($deck->name, $data);
    }

    protected function convertToCsv($filename, $data)
    {
        $output = fopen('php://output', 'w');
        if ($output === false) {
            throw new Exception('Failed to open php://output');
        }

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'"');

        fputcsv($output, array_shift($data));

        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }
}
