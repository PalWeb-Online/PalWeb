<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use App\Models\Deck;
use App\Models\Dialect;
use App\Models\Location;
use App\Models\Pronunciation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Support\Facades\View;

class RecordWizardController extends Controller
{
    public function index()
    {
        View::share('pageTitle', 'Record Wizard');

        return view('record.index', [
            'layout' => 'app'
        ]);
    }

    public function getSavedDecks(Request $request)
    {
        try {
            $user = $request->user();
            $decks = Deck::select('decks.*')
                ->join('markable_bookmarks', function ($join) use ($user) {
                    $join->on('decks.id', '=', 'markable_bookmarks.markable_id')
                        ->where('markable_bookmarks.markable_type', '=', Deck::class)
                        ->where('markable_bookmarks.user_id', '=', $user->id);
                })
                ->orderBy('markable_bookmarks.id')
                ->get();

            return response()->json(['decks' => $decks]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch saved decks.'], 500);
        }
    }

    public function getDeckItems(Request $request, $deckId)
    {
        try {
            $user = $request->user();
            $deck = Deck::firstWhere('id', $deckId);
            $pronunciations = [];

            foreach ($deck->terms as $term) {
                foreach ($term->pronunciations as $pronunciation) {
                    if ($pronunciation->dialect_id === $user->speaker->dialect_id) {
                        $alreadyRecorded = $pronunciation->audios()
                            ->where('speaker_id', $user->id)
                            ->exists();

                        if (!$alreadyRecorded) {
                            $item = $pronunciation->toArray();
                            $item['term'] = $pronunciation->term->term;
                            $pronunciations[] = $item;
                        }
                    }
                }
            }

            return response()->json([
                'deck' => $deck,
                'items' => $pronunciations
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch deck items.'], 500);
        }
    }

    public function getAutoItems(Request $request)
    {
        $speakerId = $request->input('speaker_id');
        $dialectId = $request->input('dialect_id');
        $queued = $request->input('queuedItems', []);
        $queuedIds = collect($queued)->pluck('id');
        $highestId = $queuedIds->max();

        $query = Pronunciation::where('dialect_id', $dialectId)
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

    public function uploadRecords(Request $request)
    {
        $stashkey = $request->input('stashkey');

        $speaker = $request->input('speaker');
        $pronunciation = $request->input('pronunciation');

        $filename = $request->input('language')
            .'-'.
            Dialect::find($speaker['dialect_id'])->name
            .'-'.
            Location::find($speaker['location_id'])->name
            .'-'.
            $pronunciation['translit']
            .'-'.
            $speaker['id'];

        $stashPath = public_path("stash/{$stashkey}");

        if (!FileFacade::exists($stashPath)) {
            return response()->json(['message' => 'File not found in stash.'], 404);

        } else {
//            Storage::disk('s3')->putFileAs('uploads', new File($stashPath), $filename);
            FileFacade::delete($stashPath);

            $audio = Audio::create([
                'filename' => $filename,
                'speaker_id' => $speaker['id'],
                'pronunciation_id' => $pronunciation['id'],
            ]);

            return response()->json([
                'success' => true,
                'id' => $audio->id,
                'url' => $audio->url(),
                'message' => 'File uploaded successfully',
            ]);
        }
    }

    public function updatePreferences(Request $request)
    {
        $user = auth()->user();
        $user->preferences = $request->input('preferences');
        $user->save();

        return response()->json(['status' => 'success']);
    }

    public function stashRecord(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:wav|max:5120'
        ]);

        try {
            $file = $request->file('file');
            $filename =
                $request->input('speakerId') . '_' .
                $file->getClientOriginalName();

            $file->storeAs('stash', $filename, 'public');

            return response()->json([
                'message' => 'File stashed successfully.',
                'stashkey' => $filename,
                'url' => asset("stash/{$filename}"),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to stash the file.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function discardRecord($stashkey)
    {
        $filePath = public_path("stash/{$stashkey}");

        if (FileFacade::exists($filePath)) {
            FileFacade::delete($filePath);
            return response()->json(['message' => "Recording with stashkey {$stashkey} discarded successfully."], 200);

        } else {
            return response()->json(['message' => "Recording with stashkey {$stashkey} not found."], 404);
        }
    }

    public function clearStash($speakerId)
    {
        $path = public_path('stash');

        if (FileFacade::isDirectory($path)) {
            $files = FileFacade::files($path);

            foreach ($files as $file) {
                if (str_starts_with($file->getFilename(), $speakerId.'_')) {
                    FileFacade::delete($file);
                }
            }

            return response()->json(['message' => 'Stash directory cleaned up successfully.'], 200);

        } else {
            return response()->json(['message' => 'Stash directory not found.'], 404);
        }
    }
}
