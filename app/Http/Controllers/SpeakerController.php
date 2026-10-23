<?php

namespace App\Http\Controllers;

use App\Http\Resources\AudioResource;
use App\Http\Resources\SpeakerResource;
use App\Models\Audio;
use App\Models\Speaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SpeakerController extends Controller
{
    public function show(): JsonResponse
    {
        $speaker = Speaker::firstWhere('user_id', auth()->user()?->id)?->load(['dialect']);

        return response()->json([
            'speaker' => $speaker ? new SpeakerResource($speaker) : null,
        ]);
    }

    public function getAudios(Request $request, Speaker $speaker): JsonResponse
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

    public function store(Request $request): JsonResponse
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

        return response()->json([
            'term' => new SpeakerResource($speaker),
        ]);
    }
}
