<?php

namespace App\Http\Controllers;

use App\Http\Resources\AudioResource;
use App\Models\Audio;
use App\Models\Dialect;
use App\Models\Location;
use App\Services\AudioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\URL;

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

    public function destroy(Request $request, Audio $audio): RedirectResponse|JsonResponse
    {
        if (! $request->user() || $audio->speaker->user_id !== auth()->id()) {
            return $request->expectsJson()
                ? response()->json(['error' => 'Unauthorized.'], 403)
                : abort(403, 'Unauthorized');
        }

        try {
            $this->audioService->deleteAudio($audio->filename);
            $audio->delete();

            session()->flash('notification', ['type' => 'success', 'message' => __('deleted', ['thing' => $audio->filename])]);

            return back();

        } catch (\Exception $e) {
            session()->flash('notification', ['type' => 'error', 'message' => 'Unable to delete file from cloud storage.']);

            return back();
        }
    }
}