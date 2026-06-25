<?php

namespace App\Http\Controllers;

use App\Http\Resources\AudioResource;
use App\Models\Audio;
use App\Models\Dialect;
use App\Models\Location;
use App\Services\AudioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\URL;
use Throwable;

class AudioController extends Controller
{
    public function __construct(
        protected AudioService $audioService
    ) {}

    public function index(): \Inertia\Response
    {
        return Inertia::render('Library/Audios/Index');
    }

    // -------------------------------------------------------------------------
    // API Methods
    // -------------------------------------------------------------------------
    public function apiIndex(Request $request): JsonResponse
{
        URL::forceScheme('https');

    $filters = array_merge(['sort' => 'latest'], $request->only([
        'location', 'dialect', 'gender', 'sort',
    ]));

    $query = Audio::query()
        ->with(['speaker', 'pronunciation.term'])
        ->filter($filters);

    if ($filters['sort'] === 'fluency') {
        $query->orderByFluency();
    } else {
        $query->orderByDesc('id');
    }

    $audios = $query->paginate(25)->onEachSide(1)->appends($filters);
    $resource = AudioResource::collection($audios);

    return response()->json([
        'audios' => [
            'data' => $resource->toArray($request),
            'meta' => [
                'links' => $audios->linkCollection()->toArray(),
                'current_page' => $audios->currentPage(),
                'last_page' => $audios->lastPage(),
                'total' => $audios->total(),
            ],
        ],
        'dialects' => Dialect::whereHas('speakers.audios')->get(),
        'locations' => Location::whereHas('speakers.audios')->get()->makeHidden('coordinates'),
        'totalCount' => $audios->total(),
        'filters' => $filters,
    ]);
}

    // -------------------------------------------------------------------------

    public function destroy(Audio $audio): JsonResponse
    {
        try {
            Gate::authorize('delete', $audio);

            $deletedAudio = $audio->filename;

            DB::transaction(function () use ($audio) {
                $this->audioService->deleteAudio($audio->filename);
                $audio->delete();
            });

            return response()->json([
                'success' => true,
                'message' => __('deleted', ['thing' => $deletedAudio]),
            ]);

        } catch (Throwable $e) {
            Log::error('Failed to delete Audio.', [
                'audio_id' => $audio->id,
                'exception' => $e,
            ]);

            return $this->failureJsonResponse('Unable to delete Audio.', $e);
        }
    }
}
