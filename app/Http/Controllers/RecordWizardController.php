<?php

namespace App\Http\Controllers;

use App\Models\Deck;
use App\Models\Dialect;
use App\Models\Pronunciation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Inertia\Inertia;

class RecordWizardController extends Controller
{
    public function index(): \Inertia\Response
    {
        View::share('pageTitle', 'Record Wizard');

        return Inertia::render('Workbench/RecordWizard', [
            'section' => 'workbench',
        ]);
    }

    public function getAutoItems(Request $request): JsonResponse
    {
        $speakerId = $request->input('speaker_id');
        $speakerDialect = $request->input('dialect_id');
        $dialectIds = Dialect::find($speakerDialect)->ancestors->pluck('id')->push($speakerDialect)->toArray();

        $queued = $request->input('queuedItems', []);
        $queuedIds = collect($queued)->pluck('id');
        $highestId = $queuedIds->max();

        $query = Pronunciation::whereIn('dialect_id', $dialectIds)
            ->whereNotIn('id', $queuedIds)
            ->whereDoesntHave('audios', function ($query) use ($speakerId) {
                $query->where('speaker_id', $speakerId);
            });

        if ($highestId) {
            $query->where('id', '>', $highestId);
        }

        $query->withCount('audios')
            ->orderBy('audios_count')
            ->orderBy('id');

        $limit = max(0, 100 - count($queued));
        $pronunciations = $query->take($limit)->get()->map(function ($pronunciation) {
            return array_merge($pronunciation->toArray(), [
                'term' => $pronunciation->term->term,
            ]);
        });

        return response()->json([
            'items' => $pronunciations,
        ]);
    }

    public function getDeckItems(Request $request, $deckId): JsonResponse
    {
        try {
            $speakerId = $request->input('speaker_id');
            $speakerDialect = $request->input('dialect_id');
            $dialectIds = Dialect::find($speakerDialect)->ancestors->pluck('id')->push($speakerDialect)->toArray();

            $queued = $request->input('queuedItems', []);
            $queuedIds = collect($queued)->pluck('id');

            $deck = Deck::firstWhere('id', $deckId);
            $pronunciations = [];

            foreach ($deck->terms as $term) {
                foreach ($term->pronunciations as $pronunciation) {
                    if (
                        in_array($pronunciation->dialect_id, $dialectIds) &&
                        ! $queuedIds->contains($pronunciation->id)
                    ) {
                        $alreadyRecorded = $pronunciation->audios()
                            ->where('speaker_id', $speakerId)
                            ->exists();

                        if (! $alreadyRecorded) {
                            $item = $pronunciation->toArray();
                            $item['term'] = $pronunciation->term->term;
                            $pronunciations[] = $item;

                            if (count($queued) + count($pronunciations) >= 100) {
                                break 2;
                            }
                        }
                    }
                }
            }

            return response()->json([
                'deck' => $deck,
                'items' => $pronunciations,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch deck items.'], 500);
        }
    }
}
