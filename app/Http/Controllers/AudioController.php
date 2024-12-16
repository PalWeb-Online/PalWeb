<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use App\Models\Dialect;
use App\Models\Location;
use App\Models\Speaker;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;

class AudioController extends Controller
{
    public function __construct(
        protected FlasherInterface $flasher,
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
            'bodyBackground' => 'purple-pastel',
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
            'bodyBackground' => 'purple-pastel'
        ]);
    }

    public function destroy(Audio $audio)
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($audio->speaker->user_id === $user->id) {
                $success = $this->deleteRemote($audio->filename);

                if ($success) {
                    $audio->delete();

                    if (request()->expectsJson()) {
                        return response()->json(['message' => __('deleted', ['thing' => $audio->filename])]);

                    } else {
                        $this->flasher->addSuccess(__('deleted', ['thing' => $audio->filename]));
                    }

                } else {
                    if (request()->expectsJson()) {
                        return response()->json(['error' => 'Unable to delete file from cloud storage.'], 500);

                    } else {
                        $this->flasher->addError('Unable to delete file from cloud storage.');
                    }
                }

                return to_route('audios.speaker', $user->speaker);

            } else {
                if (request()->expectsJson()) {
                    response()->json(['error' => 'Unauthorized.'], 403);
                }
            }

        } else {
            if (request()->expectsJson()) {
                response()->json(['error' => 'Unauthorized.'], 403);
            }
        }
    }

    private function deleteRemote(string $filename)
    {
//        TODO: Delete file from cloud server.
        return true;
    }
}
