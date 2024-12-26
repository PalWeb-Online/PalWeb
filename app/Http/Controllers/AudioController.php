<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use App\Models\Dialect;
use App\Models\Location;
use App\Models\Speaker;
use App\Services\AudioService;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;

class AudioController extends Controller
{
    public function __construct(
        protected FlasherInterface $flasher,
        protected AudioService $audioService
    ) {
    }

    public function index(Request $request)
    {
        $sort = $request->input('sort', 'latest');
        $query = Audio::with(['speaker', 'pronunciation.term']);

        if ($request->filled('location')) {
            $query->whereHas('speaker', function ($q) use ($request) {
                $q->where('location_id', $request->input('location'));
            });
        }

        if ($request->filled('dialect')) {
            $query->whereHas('speaker', function ($q) use ($request) {
                $q->where('dialect_id', $request->input('dialect'));
            });
        }

        if ($request->filled('gender')) {
            $query->whereHas('speaker', function ($q) use ($request) {
                $q->where('gender', $request->input('gender'));
            });
        }

        if ($sort === 'fluency') {
            $query->orderByFluency();
        } else {
            $query->orderByDesc('id');
        }

        $audios = $query->paginate(50)->onEachSide(1);
        $totalCount = $audios->total();

        return view('community.audios.index', [
            'audios' => $audios,
            'totalCount' => $totalCount,
            'locations' => Location::whereHas('speakers.audios')->get(),
            'dialects' => Dialect::whereHas('speakers.audios')->get(),
            'currentSort' => $sort,
        ]);
    }

    public function speaker(Speaker $speaker)
    {
        $audios = $speaker->audios()
            ->with('speaker')
            ->orderByDesc('id')
            ->paginate(25)
            ->onEachSide(1);

        return view('community.audios.speaker', [
            'user' => $speaker->user,
            'speaker' => $speaker,
            'audios' => $audios,
        ]);
    }

    public function destroy(Audio $audio)
    {
        if (!auth()->check() || $audio->speaker->user_id !== auth()->id()) {
            return request()->expectsJson()
                ? response()->json(['error' => 'Unauthorized.'], 403)
                : abort(403, 'Unauthorized');
        }

        try {
            $this->audioService->deleteAudio($audio->filename);
            $audio->delete();

            $message = __('deleted', ['thing' => $audio->filename]);

            if (request()->expectsJson()) {
                return response()->json(['message' => $message]);
            } else {
                $this->flasher->addSuccess($message);
                return redirect()->route('audios.speaker', $audio->speaker);
            }

        } catch (\Exception $e) {
            $error = 'Unable to delete file from cloud storage.';

            if (request()->expectsJson()) {
                return response()->json(['error' => $error, 'details' => $e->getMessage()], 500);
            } else {
                $this->flasher->addError($error);
                return redirect()->route('audios.speaker', $audio->speaker);
            }
        }
    }
}
