<?php

namespace App\Http\Controllers;

use App\Events\ModelPinned;
use App\Models\Gloss;
use App\Models\Sentence;
use App\Models\Term;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Maize\Markable\Models\Bookmark;

class SentenceController extends Controller
{
    public function __construct(protected FlasherInterface $flasher)
    {
    }

    public function pin(Sentence $sentence)
    {
        $user = auth()->user();

        Bookmark::toggle($sentence, $user);

        if (Bookmark::has($sentence, $user)) {
            event(new ModelPinned($user));
            $this->flasher->addSuccess(__('pin.added', ['thing' => $sentence->translit]));
        } else {
            $this->flasher->addSuccess(__('pin.removed', ['thing' => $sentence->translit]));
        }

        return back();
    }

    public function index(Request $request)
    {
        View::share('pageTitle', 'the Phrasebook');
        View::share('pageDescription',
            'Discover the Phrasebook, a vast corpus of Palestinian Arabic within the PalWeb Dictionary. Search and learn from real-life examples, seeing words in action for effective language mastery.');

        $query = Sentence::query();

        if ($searchTerm = $request->search) {
            $query->where('sentence', 'LIKE', '%'.$searchTerm.'%');
        }

        $sentences = $query
            ->orderByDesc('created_at')
            ->paginate(25)
            ->onEachSide(1);

        $totalCount = $sentences->total();

        return view('sentences.index', compact('sentences', 'totalCount'));
    }

    public function show(Sentence $sentence)
    {
        View::share('pageTitle', 'Sentence: '.$sentence->translit);
        View::share('pageDescription',
            'Discover the Sentence Library, a vast corpus of Palestinian Arabic. Search and learn from real-life examples, seeing words in action for effective language mastery.');

        return view('sentences.show', [
            'sentence' => $sentence
        ]);
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);
        $sentence = $request->sentence;
        $sentence = Sentence::create($sentence);

        $this->flasher->addSuccess(__('created', ['thing' => $sentence->translit]));
        return to_route('sentences.index');
    }

    private function validateRequest($request)
    {
        return $request->validate([
            'sentence.sentence' => ['required'],
            'sentence.translit' => ['required'],
            'sentence.trans' => ['required'],
        ]);
    }

    public function create()
    {
        View::share('pageTitle', 'Create Sentence');
        return view('sentences.create');
    }

    public function edit(Sentence $sentence)
    {
        View::share('pageTitle', 'Edit Sentence');
        return view('sentences.edit', compact('sentence'));
    }

    public function update(Sentence $sentence, Request $request)
    {
        $this->validateRequest($request);

        $sentence->update($request->sentence);

        $this->flasher->addSuccess(__('updated', ['thing' => $sentence->translit]));

        return view('sentences.show', [
            'sentence' => $sentence
        ]);
    }

    public function destroy(Sentence $sentence)
    {
        $sentence->delete();

        $this->flasher->addSuccess(__('deleted', ['thing' => $sentence->translit]));
        return to_route('sentences.index');
    }

    public function todo()
    {
        $withEligibleSentences = [];
        foreach (Sentence::all() as $sentence) {
            foreach ($sentence->getTerms() as $term) {
                if (!is_array($term) && !$term->glosses()->whereHas('sentences')->exists()) {
                    $withEligibleSentences[] = $term;
                }
            }
        }
        $withEligibleSentences = collect($withEligibleSentences);
        $withEligibleSentences = $withEligibleSentences->unique('id');


        $termsMissingSentences = [];
        foreach (Gloss::doesntHave('sentences')->get() as $gloss) {
            $term = Term::firstWhere('id', $gloss->term_id);
            $term->gloss = $gloss->gloss;
            $termsMissingSentences[] = $term;
        }
        $termsMissingSentences = collect($termsMissingSentences);

        $orphanSentences = Sentence::doesntHave('glosses')->get();

        View::share('pageTitle', 'Phrasebook: to-Do');
        return view('sentences.todo', [
            'termsMissingSentences' => $termsMissingSentences,
            'orphanSentences' => $orphanSentences,
            'withEligibleSentences' => $withEligibleSentences
        ]);
    }
}
