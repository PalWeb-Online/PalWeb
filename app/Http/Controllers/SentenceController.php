<?php

namespace App\Http\Controllers;

use App\Events\ModelPinned;
use App\Models\Gloss;
use App\Models\Sentence;
use App\Models\Term;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
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
        return to_route('sentences.index');

    }

    public function destroy(Sentence $sentence)
    {
        $sentence->delete();

        $this->flasher->addSuccess(__('deleted', ['thing' => $sentence->translit]));
        return to_route('sentences.index');
    }

    public function todo()
    {
        $termsMissingSentences = new Collection();
        foreach (Gloss::doesntHave('sentences')->get() as $gloss) {
            $termsMissingSentences = $termsMissingSentences->merge(Term::where('id', $gloss->term_id)->get());
        }
        $termsMissingSentences = $termsMissingSentences->unique();

        $audioFiles = Storage::disk('s3')->allFiles('audio');
        $audios = [];
        foreach ($audioFiles as $audio) {
            $audio = str_replace('audio/', '', $audio);
            $audio = str_replace('.mp3', '', $audio);
            $audios[] = $audio;
        }

        $sentencesMissingAudios = new Collection();
        foreach (Sentence::all() as $sentence) {
            if (!in_array($sentence->translit, $audios)) {
                $sentencesMissingAudios =
                    $sentencesMissingAudios->merge(Sentence::where('sentence', $sentence->sentence)->get());
            }
            preg_match_all('/(\[{2}[^\]]*\]{2})/', $sentence->sentence, $matches);
            $sentenceTerms = $matches[0];
            foreach ($sentenceTerms as $sentenceTerm) {
                preg_match('/\[{2}([^|\]]*)\|([^\]]*)\]{2}/', $sentenceTerm, $matches);
                $termsArray = $termsArray->merge($matches[1]);
            }
        }

        $orphanSentences = Sentence::doesntHave('glosses')->get();

        View::share('pageTitle', 'to-Do');
        return view('sentences.todo', [
            'termsMissingSentences' => $termsMissingSentences,
            'sentencesMissingAudios' => $sentencesMissingAudios,
            'orphanSentences' => $orphanSentences
        ]);
    }
}
