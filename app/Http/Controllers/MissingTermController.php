<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMissingTermRequest;
use App\Models\Inflection;
use App\Models\MissingTerm;
use App\Models\Term;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class MissingTermController extends Controller
{
    public function __construct(
        protected FlasherInterface $flasher,
    ) {}

    public function index(): \Illuminate\View\View
    {
        $missingInflections = [];
        foreach (Inflection::whereIn('form', ['ap', 'pp', 'nv'])->get() as $inflection) {
            if (Term::where('translit', $inflection->translit)->doesntExist()) {
                $missingInflections[] = $inflection;
            }
        }
        $missingInflections = collect($missingInflections);

        View::share('pageTitle', 'Dictionary: to-Do');

        return view('terms.todo', [
            'fromSentences' => DB::table('sentence_term')->whereNull('term_id')->get(),
            'missingTerms' => MissingTerm::all(),
            'missingInflections' => $missingInflections,
        ]);
    }

    public function create(): \Illuminate\View\View
    {
        View::share('pageTitle', 'Request Term');

        return view('terms.request');
    }

    public function store(StoreMissingTermRequest $request): RedirectResponse
    {
        MissingTerm::create([
            'translit' => $request['translit'],
            'category' => $request['category'],
        ]);

        $this->flasher->addSuccess(__('missing.terms.created'));

        return to_route('terms.index');
    }

    public function destroy(MissingTerm $missingTerm): RedirectResponse
    {
        $missingTerm->delete();

        $this->flasher->addSuccess(__('deleted', ['thing' => $missingTerm->translit]));

        return to_route('missing.terms.index');
    }
}
