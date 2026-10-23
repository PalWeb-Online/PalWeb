<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeckResource;
use App\Http\Resources\LessonResource;
use App\Http\Resources\UnitResource;
use App\Models\Deck;
use App\Models\Lesson;
use App\Models\Unit;
use App\Services\LessonService;
use App\Services\TermService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LessonPlannerController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'units' => UnitResource::collection(Unit::all()),
            'lessons' => LessonResource::collection(Lesson::where('unit_id', null)->get()),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'units' => ['required', 'array'],
            'units.*.id' => ['nullable', 'integer', 'exists:units,id'],
            'units.*.title' => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated) {
            $incomingUnits = collect($validated['units']);

            Unit::whereIn('id', $incomingUnits->pluck('id')->filter())->each(function ($unit) {
                $unit->update(['position' => $unit->position + 1000]);
            });

            foreach ($incomingUnits as $index => $unitData) {
                $position = $index + 1;

                if (!empty($unitData['id'])) {
                    Unit::whereKey($unitData['id'])->update([
                        'title' => $unitData['title'],
                        'position' => $position,
                    ]);

                } else {
                    Unit::create([
                        'title' => $unitData['title'],
                        'position' => $position,
                    ]);
                }
            }

            LessonService::reorderAllUnitsAndLessons();
        });

        return response()->json([
            'success' => true,
        ]);
    }

    public function unitDecks(Unit $unit, TermService $termService): JsonResponse
    {
        $deckIds = $unit->lessons()
            ->whereNotNull('deck_id')
            ->orderBy('unit_position')
            ->pluck('deck_id')
            ->unique()
            ->values();

        $decks = Deck::query()
            ->whereKey($deckIds)
            ->with([
                'terms' => fn ($q) => $q
                    ->whereNotNull('deck_term.gloss_id')
                    ->withItemData(),
            ])
            ->get()
            ->sortBy(fn (Deck $deck) => $deckIds->search($deck->getKey()))
            ->values();

        $decks->each(fn (Deck $deck) => $termService->hydratePronunciations($deck->terms));

        return response()->json([
            'unit' => new UnitResource($unit),
            'decks' => DeckResource::collection($decks),
        ]);
    }
}
