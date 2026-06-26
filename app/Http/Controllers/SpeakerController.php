<?php

namespace App\Http\Controllers;

use App\Http\Resources\AudioResource;
use App\Http\Resources\SpeakerResource;
use App\Models\Audio;
use App\Models\Dialect;
use App\Models\Location;
use App\Models\Speaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SpeakerController extends Controller
{
    public function show(Speaker $speaker): \Inertia\Response
    {
        return Inertia::render('Library/Audios/Speaker', [
            'speakerId' => $speaker->id,
        ]);
    }

    // -------------------------------------------------------------------------
    // API Methods
    // -------------------------------------------------------------------------

    public function fetch(Request $request, Speaker $speaker): JsonResponse
    {
        $includes = collect(explode(',', (string) $request->query('include')))
            ->map(fn (string $include) => trim($include))
            ->filter()
            ->values();

        $speaker->load(['dialect'])->loadCount(['audios']);

        $payload = [
            'speaker' => new SpeakerResource($speaker),
        ];

        if ($includes->contains('show') || $includes->isEmpty()) {
            $perPage = 25;
            $currentPage = $request->integer('page', 1);

            $audiosCollection = Audio::query()
                ->where('speaker_id', $speaker->id)
                ->with([
                    'speaker',
                    'pronunciation.term',
                ])
                ->orderByDesc('id')
                ->get();

            $audios = new \Illuminate\Pagination\LengthAwarePaginator(
                $audiosCollection->forPage($currentPage, $perPage)->values(),
                $audiosCollection->count(),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            $resource = AudioResource::collection($audios);

            $payload['audios'] = [
                'data' => $resource->toArray($request),
                'meta' => [
                    'links' => $audios->linkCollection()->toArray(),
                    'current_page' => $audios->currentPage(),
                    'last_page' => $audios->lastPage(),
                    'total' => $audios->total(),
                ],
            ];
        }

        return response()->json($payload);
    }

    // -------------------------------------------------------------------------

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        $speaker = Speaker::updateOrCreate(
            ['user_id' => $user->id],
            [
                'dialect_id' => $request->input('dialect_id'),
                'location_id' => $request->input('location_id'),
                'fluency' => $request->input('fluency'),
                'gender' => $request->input('gender'),
            ]
        );
        $user->update(['speaker_id' => $speaker->id]);

        session()->flash('notification',
            ['type' => 'success', 'message' => 'Your Speaker profile has been saved!']);

        return to_route('sound-booth.index');
    }

    public function getSpeakerOptions(): JsonResponse
    {
        return response()->json([
            'dialects' => Dialect::find([6, 7, 8, 9, 10, 11, 12])->toArray(),
            'locations' => Location::all()->makeHidden('coordinates')->toArray(),
        ]);
    }
}
