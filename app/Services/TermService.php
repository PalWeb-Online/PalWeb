<?php

namespace App\Services;

use App\Models\Sentence;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TermService
{
    public function __construct(
        protected DialectService $dialectService,
    ) {
    }

    public function hydratePronunciations(Collection $terms): Collection
    {
        $dialectIds = $this->dialectService->dialectIds();

        return $terms->each(function ($term) use ($dialectIds) {
            if (! $term->relationLoaded('pronunciations')) {
                return;
            }

            $selected = $this->selectPronunciation(
                $term->pronunciations,
                $dialectIds
            );

            $term->selected_audio =
                $selected?->audios?->first()?->url;

            $term->selected_translit =
                $selected?->translit;

            $term->selected_pronunciation = $selected;
        });
    }


    public function selectPronunciation(Collection $pronunciations, Collection $dialectIds)
    {
        if ($pronunciations->isEmpty()) {
            return null;
        }

        $userPronunciations = $pronunciations
            ->whereIn('dialect_id', $dialectIds);

        $pronunciationsWithAudio = $pronunciations
            ->filter(fn ($pronunciation) => $pronunciation->audios->isNotEmpty());

        return
            $userPronunciations->first(
                fn ($pronunciation) => $pronunciation->audios->isNotEmpty()
            )
            ?? $pronunciationsWithAudio->first()
            ?? $userPronunciations->first()
            ?? $pronunciations->first();
    }

    public function hydrateGlossSentences(Collection $terms): Collection
    {
        $termIds = $terms->pluck('id')->filter()->unique();

        $sentenceData = DB::table('sentence_term')
            ->whereIn('term_id', $termIds)
            ->groupBy('term_id', 'gloss_id')
            ->selectRaw('
                term_id,
                gloss_id,
                MIN(sentence_id) as sentence_id,
                COUNT(*) as sentences_count
            ')
            ->get();

        $sentenceIds = $sentenceData
            ->pluck('sentence_id')
            ->filter()
            ->unique();

        $sentences = Sentence::query()
            ->whereIn('id', $sentenceIds)
            ->with('dialog')
            ->get()
            ->keyBy('id');

        $glossSentenceMap = [];

        foreach ($sentenceData as $row) {
            $sentence = $sentences->get($row->sentence_id);

            if (! $sentence) {
                continue;
            }

            $glossSentenceMap[$row->term_id][$row->gloss_id] ??= [
                'sentences' => [],
                'sentences_count' => $row->sentences_count,
            ];

            $glossSentenceMap[$row->term_id][$row->gloss_id]['sentences'][] = $sentence;
        }

        return $terms->each(function ($term) use ($glossSentenceMap) {
            $term->gloss_sentences = $glossSentenceMap[$term->id] ?? [];
        });
    }
}
