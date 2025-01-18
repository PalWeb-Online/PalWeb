<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStashRecordRequest;
use App\Models\Audio;
use App\Services\AudioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class StashRecordController extends Controller
{
    public function __construct(protected AudioService $audioService) {}

    public function store(StoreStashRecordRequest $request): JsonResponse
    {
        try {
            $file = $request->file('file');
            $stashKey =
                $request->input('speakerId').'_'.
                $file->getClientOriginalName();

            $file->storeAs('stash', $stashKey, 'public');

            return response()->json([
                'message' => 'File stashed successfully.',
                'stashKey' => $stashKey,
                'url' => Storage::disk('public')->url("stash/{$stashKey}"),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to stash the file.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($stashKey): JsonResponse
    {
        $stashPath = Storage::disk('public')->path("stash/{$stashKey}");

        if (File::exists($stashPath)) {
            File::delete($stashPath);

            return response()->json(['message' => "Recording with stashKey {$stashKey} discarded successfully."], 200);

        } else {
            return response()->json(['message' => "Recording with stashKey {$stashKey} not found."], 404);
        }
    }

    public function upload(Request $request): JsonResponse
    {
        $filename = $request->input('filename').'.mp3';
        $stashPath = Storage::disk('public')->path("stash/{$request->input('stashKey')}");

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
                'message' => 'File uploaded successfully',
                'id' => $audio->id,
                'url' => $audio->url(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to upload file.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function clearStash($speakerId): JsonResponse
    {
        $path = Storage::disk('public')->path('stash');

        if (File::isDirectory($path)) {
            $files = File::files($path);

            foreach ($files as $file) {
                if (str_starts_with($file->getFilename(), $speakerId.'_')) {
                    File::delete($file);
                }
            }

            return response()->json(['message' => 'Stash directory cleaned up successfully.']);

        } else {
            return response()->json(['message' => 'Stash directory not found.'], 404);
        }
    }
}
