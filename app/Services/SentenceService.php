<?php

namespace App\Services;

use App\Http\Resources\TermResource;
use App\Models\Sentence;
use App\Models\Term;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SentenceService
{
    public function __construct(
        protected TermService $termService,
    ) {
    }

    public function getSentenceTerms(Sentence $sentence): Collection
    {
        $allTerms = DB::table('sentence_term')
            ->leftJoin('terms', 'sentence_term.term_id', '=', 'terms.id')
            ->where('sentence_term.sentence_id', $sentence->id)
            ->select('sentence_term.*', 'terms.*')
            ->orderBy('sentence_term.position')
            ->get();

        $termIds = $allTerms
            ->pluck('id')
            ->filter()
            ->unique();

        $terms = Term::query()
            ->withItemData()
            ->withUserCard()
            ->whereIn('id', $termIds)
            ->get()
            ->keyBy('id');

        $this->termService->hydratePronunciations($terms);

        return $allTerms->map(function ($sentenceTerm) use ($terms) {
            if ($sentenceTerm->id) {
                $termResource = new TermResource($terms[$sentenceTerm->id])->toArray(request());
                $termResource['sentencePivot'] = [
                    'uuid' => $sentenceTerm->uuid,
                    'gloss_id' => $sentenceTerm->gloss_id,
                    'sent_term' => $sentenceTerm->sent_term,
                    'sent_translit' => $sentenceTerm->sent_translit,
                    'position' => $sentenceTerm->position,
                    'toggleable' => $sentenceTerm->toggleable,
                ];

                return $termResource;
            }

            return [
                'sentencePivot' => [
                    'uuid' => $sentenceTerm->uuid,
                    'sent_term' => $sentenceTerm->sent_term,
                    'sent_translit' => $sentenceTerm->sent_translit,
                    'position' => $sentenceTerm->position,
                    'toggleable' => $sentenceTerm->toggleable,
                ],
            ];
        })->values();
    }
}
