<?php

namespace App\Http\Controllers\Workbench;

use App\Http\Controllers\Controller;
use App\Http\Resources\PronunciationResource;
use App\Http\Resources\SpeakerResource;
use App\Models\Deck;
use App\Models\Dialect;
use App\Models\Pronunciation;
use App\Models\Speaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SoundBoothController extends Controller
{
    public function index(): \Inertia\Response
    {
        $speaker = Speaker::firstWhere('user_id', auth()->user()?->id)?->load(['dialect']);

        return Inertia::render('Workbench/SoundBooth/SoundBooth', [
            'section' => 'workbench',
            'speaker' => $speaker ? new SpeakerResource($speaker) : null,
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

        $query = Pronunciation::with(['term'])
            ->whereIn('dialect_id', $dialectIds)
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

        return response()->json([
            'items' => PronunciationResource::collection($query->take($limit)->get()),
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

            $deck = Deck::firstWhere('id', $deckId)->load(['terms.pronunciations']);
            $pronunciationsToQueue = [];

            foreach ($deck->terms as $term) {
                $termPronunciations = $term->pronunciations->load(['term']);

                foreach ($termPronunciations as $pronunciation) {
                    if (count($queued) + count($pronunciationsToQueue) >= 100) {
                        session()->flash('notification',
                            ['type' => 'success', 'message' => 'You\'ve reached the Queue max. Some items could not be added.']);
                        break 2;
                    }

                    if (
                        in_array($pronunciation->dialect_id, $dialectIds) &&
                        ! $queuedIds->contains($pronunciation->id)
                    ) {
                        $alreadyRecorded = $pronunciation->audios()
                            ->where('speaker_id', $speakerId)
                            ->exists();

                        if (! $alreadyRecorded) {
                            $pronunciationsToQueue[] = $pronunciation;
                        }
                    }
                }
            }

            return response()->json([
                'items' => PronunciationResource::collection($pronunciationsToQueue),
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch deck items.'], 500);
        }
    }
}
