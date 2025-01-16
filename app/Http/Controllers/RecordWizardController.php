<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use App\Models\Deck;
use App\Models\Dialect;
use App\Models\Pronunciation;
use App\Services\AudioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

class RecordWizardController extends Controller
{
    public function __construct(protected AudioService $audioService) {}

    public function index()
    {
        View::share('pageTitle', 'Record Wizard');

        return view('users.record.index', [
            'layout' => 'app',
        ]);
    }

    public function getAutoItems(Request $request)
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

    public function getDeckItems(Request $request, $deckId)
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

    public function stashRecord(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:wav|max:5120',
        ]);

        try {
            $file = $request->file('file');
            $stashKey =
                $request->input('speakerId').'_'.
                $file->getClientOriginalName();

            $file->storeAs('stash', $stashKey, 'public');

            return response()->json([
                'message' => 'File stashed successfully.',
                'stashKey' => $stashKey,
                'url' => asset("stash/{$stashKey}"),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to stash the file.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function discardRecord($stashKey)
    {
        $filePath = public_path("stash/{$stashKey}");

        if (File::exists($filePath)) {
            File::delete($filePath);

            return response()->json(['message' => "Recording with stashKey {$stashKey} discarded successfully."], 200);

        } else {
            return response()->json(['message' => "Recording with stashKey {$stashKey} not found."], 404);
        }
    }

    public function clearStash($speakerId)
    {
        $path = public_path('stash');

        if (File::isDirectory($path)) {
            $files = File::files($path);

            foreach ($files as $file) {
                if (str_starts_with($file->getFilename(), $speakerId.'_')) {
                    File::delete($file);
                }
            }

            return response()->json(['message' => 'Stash directory cleaned up successfully.'], 200);

        } else {
            return response()->json(['message' => 'Stash directory not found.'], 404);
        }
    }

    public function uploadRecords(Request $request)
    {
        $filename = $request->input('filename').'.mp3';
        $stashPath = public_path("stash/{$request->input('stashKey')}");

        if (! File::exists($stashPath)) {
            return response()->json(['message' => 'File not found in stash.'], 404);
        }

        try {
            $this->audioService->uploadAudio($stashPath, $filename);
            File::delete($stashPath);

            $audio = Audio::create([
                'filename' => $filename,
                'speaker_id' => $request->input('speaker')['id'],
                'pronunciation_id' => $request->input('pronunciation')['id'],
            ]);

            return response()->json([
                'success' => true,
                'id' => $audio->id,
                'url' => $audio->url(),
                'message' => 'File uploaded successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to upload file.', 'error' => $e->getMessage()], 500);
        }
    }
}
