<?php

namespace App\Http\Controllers;

use App\Events\ModelPinned;
use App\Http\Requests\StoreSentenceRequest;
use App\Http\Requests\UpdateSentenceRequest;
use App\Http\Resources\SentenceResource;
use App\Jobs\UpdateTermUsageCount;
use App\Models\Sentence;
use App\Services\SearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Maize\Markable\Models\Bookmark;

class SentenceController extends Controller
{
    public function pin(Request $request, Sentence $sentence): JsonResponse
    {
        $user = $request->user();

        Bookmark::toggle($sentence, $user);

        $sentence->isPinned() && event(new ModelPinned($user));

        $message = $sentence->isPinned() ? __('pin.added', ['thing' => $sentence->sentence]) : __('pin.removed', ['thing' => $sentence->sentence]);

        return response()->json([
            'isPinned' => $sentence->isPinned(),
            'message' => $message,
        ]);
    }

    public function getMany(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer',
        ]);

        $sentences = Sentence::whereIn('id', $request->input('ids'))->get();

        return SentenceResource::collection($sentences)->keyBy('id');
    }

    public function index(Request $request, SearchService $searchService): \Inertia\Response
    {
        $filters = array_merge(['sort' => 'latest'], $request->only([
            'search', 'match', 'sort', 'pinned',
        ]));

        if (empty($filters['search'])) {
            $sentences = Sentence::query()
                ->filter($filters)
                ->paginate(25)
                ->onEachSide(1)
                ->appends($filters);
            $totalCount = $sentences->total();

        } else {
            $results = $searchService->search($filters, true, false)['sentences'];
            $totalCount = $results->count();

            $perPage = 25;
            $currentPage = $request->input('page', 1);
            $sentences = $results->forPage($currentPage, $perPage);

            $sentences = new \Illuminate\Pagination\LengthAwarePaginator(
                $sentences,
                $totalCount,
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        }

        return Inertia::render('Library/Sentences/Index', [
            'section' => 'library',
            'sentences' => SentenceResource::collection(
                $sentences->getCollection()->map(function ($sentence) {
                    return new SentenceResource($sentence)->additional(['terms' => false]);
                })
            )->additional(['meta' => $sentences->toArray()]),
            'totalCount' => $totalCount,
            'filters' => $filters,
        ]);

        //        View::share('pageDescription',
        //            'Discover the Corpus, a vast corpus of Palestinian Arabic within the PalWeb Dictionary. Search and learn from real-life examples, seeing words in action for effective language mastery.');
    }

    public function show(Sentence $sentence): \Inertia\Response
    {
        //        View::share('pageDescription',
        //            'Discover the Sentence Library, a vast corpus of Palestinian Arabic. Search and learn from real-life examples, seeing words in action for effective language mastery.');

        $sentence->load(['dialog']);

        return Inertia::render('Library/Sentences/Show', [
            'section' => 'library',
            'sentence' => new SentenceResource($sentence),
        ]);
    }

    public function store(StoreSentenceRequest $request): RedirectResponse
    {
        $sentence = Sentence::create($this->buildSentence($request->all()));
        $affectedTermIds = $this->linkTerms($sentence, $request->terms);

        UpdateTermUsageCount::dispatch($affectedTermIds);

        session()->flash('notification',
            ['type' => 'success', 'message' => __('created', ['thing' => $sentence->sentence])]);

        return to_route('speech-maker.sentence', $sentence);
    }

    public function update(UpdateSentenceRequest $request, Sentence $sentence): RedirectResponse
    {
        $sentence->update($this->buildSentence($request->all()));
        $affectedTermIds = $this->linkTerms($sentence, $request->terms);

        UpdateTermUsageCount::dispatch($affectedTermIds);

        session()->flash('notification',
            ['type' => 'success', 'message' => __('updated', ['thing' => $sentence->sentence])]);

        return to_route('speech-maker.sentence', $sentence);
    }

    private function buildSentence($sentenceData): array
    {
        $terms = [];
        $translits = [];

        foreach ($sentenceData['terms'] as $term) {
            $terms[] = $term['sentencePivot']['sent_term'];
            $translits[] = $term['sentencePivot']['sent_translit'];
        }

        $sentence = $sentenceData;

        $sentence['sentence'] = implode(' ', $terms);
        $sentence['translit'] = implode(' ', $translits);

        $dialog = $sentenceData['dialog'];
        $sentence['dialog_id'] = $dialog ? $dialog['id'] : null;

        return $sentence;
    }

    private function linkTerms(Sentence $sentence, array $terms): array
    {
        $existingRows = DB::table('sentence_term')
            ->where('sentence_id', $sentence->id)
            ->get()
            ->keyBy('uuid');

        $incomingRows = collect($terms)->map(function (array $termData) {
            return [
                'uuid' => $termData['sentencePivot']['uuid'] ?? (string) Str::uuid(),
                'term_id' => $termData['id'] ?? null,
                'gloss_id' => $termData['sentencePivot']['gloss_id'] ?? null,
                'sent_term' => $termData['sentencePivot']['sent_term'],
                'sent_translit' => $termData['sentencePivot']['sent_translit'],
                'position' => $termData['sentencePivot']['position'],
                'toggleable' => $termData['sentencePivot']['toggleable'],
            ];
        })->keyBy('uuid');

        $existingUuids = $existingRows->keys();
        $incomingUuids = $incomingRows->keys();

        $toDelete = $existingUuids->diff($incomingUuids);
        $toInsert = $incomingUuids->diff($existingUuids);
        $toUpdate = $incomingUuids->intersect($existingUuids);

        $affectedTermIds = collect();

        DB::transaction(function () use (
            $sentence,
            $existingRows,
            $incomingRows,
            $toDelete,
            $toInsert,
            $toUpdate,
            $affectedTermIds
        ) {
            foreach ($toDelete as $uuid) {
                $row = $existingRows->get($uuid);

                if ($row?->term_id) {
                    $affectedTermIds->push($row->term_id);
                }

                DB::table('sentence_term')
                    ->where('sentence_id', $sentence->id)
                    ->where('uuid', $uuid)
                    ->delete();
            }

            $insertRows = [];

            foreach ($toInsert as $uuid) {
                $row = $incomingRows->get($uuid);

                if ($row['term_id']) {
                    $affectedTermIds->push($row['term_id']);
                }

                $insertRows[] = [
                    'sentence_id' => $sentence->id,
                    'uuid' => $row['uuid'],
                    'term_id' => $row['term_id'],
                    'gloss_id' => $row['gloss_id'],
                    'sent_term' => $row['sent_term'],
                    'sent_translit' => $row['sent_translit'],
                    'position' => $row['position'],
                    'toggleable' => $row['toggleable'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if ($insertRows !== []) {
                DB::table('sentence_term')->insert($insertRows);
            }

            foreach ($toUpdate as $uuid) {
                $existing = $existingRows->get($uuid);
                $incoming = $incomingRows->get($uuid);

                if ($existing?->term_id) {
                    $affectedTermIds->push($existing->term_id);
                }

                if ($incoming['term_id']) {
                    $affectedTermIds->push($incoming['term_id']);
                }

                DB::table('sentence_term')
                    ->where('sentence_id', $sentence->id)
                    ->where('uuid', $uuid)
                    ->update([
                        'term_id' => $incoming['term_id'],
                        'gloss_id' => $incoming['gloss_id'],
                        'sent_term' => $incoming['sent_term'],
                        'sent_translit' => $incoming['sent_translit'],
                        'position' => $incoming['position'],
                        'toggleable' => $incoming['toggleable'],
                        'updated_at' => now(),
                    ]);
            }
        });

        return $affectedTermIds
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public function destroy(Sentence $sentence): RedirectResponse
    {
        $affectedTermIds = DB::table('sentence_term')
            ->where('sentence_id', $sentence->id)
            ->whereNotNull('term_id')
            ->pluck('term_id')
            ->unique()
            ->values()
            ->all();

        $sentence->delete();

        UpdateTermUsageCount::dispatch($affectedTermIds);

        session()->flash('notification',
            ['type' => 'success', 'message' => __('deleted', ['thing' => $sentence->sentence])]);

        return to_route('sentences.index');
    }
}
