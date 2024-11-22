<?php

namespace App\Http\Controllers;

use App\Models\Dialect;
use App\Models\Location;
use App\Models\Pronunciation;
use App\Models\Speaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Storage;

class RecordWizardController extends Controller
{
    public function index()
    {
        View::share('pageTitle', 'Record Wizard');

        return view('record.index', [
            'layout' => 'app'
        ]);
    }

    public function getSpeaker()
    {
        $user = auth()->user();

        $speaker = Speaker::firstWhere('user_id', $user->id) ?? [
            'user_id' => null,
            'dialect_id' => null,
            'location_id' => null,
            'gender' => null,
        ];

        return response()->json([
            'name' => $user->name,
            'speaker' => $speaker,
        ]);
    }

    public function getSpeakerOptions()
    {
        return response()->json([
            'dialects' => Dialect::all()->toArray(),
            'locations' => Location::all()->makeHidden('coordinate')->toArray(),
        ]);
    }

    public function saveSpeaker(Request $request)
    {
        $user = auth()->user();

        $speaker = Speaker::updateOrCreate(
            ['user_id' => $user->id],
            [
                'dialect_id' => $request->input('dialect_id'),
                'location_id' => $request->input('location_id'),
                'gender' => $request->input('gender')
            ]
        );

        return response()->json([
            'name' => $user->name,
            'speaker' => $speaker,
            'message' => 'Speaker profile saved successfully',
        ]);
    }

    public function getPronunciations(Request $request)
    {
        $listed = $request->input('listedPronunciations', []);
        $listedIds = collect($listed)->pluck('id');

        $currentCollection = Pronunciation::whereIn('id', $listedIds)->get();

        $files = Storage::disk('s3')->files('audio');
        $filenames = array_map(function ($file) {
            return pathinfo($file, PATHINFO_FILENAME);
        }, $files);

        $dialectId = $request->input('dialect_id');
        $highestId = $listedIds->max();

        // Base query for new pronunciations
        $query = Pronunciation::where('dialect_id', $dialectId);

        if ($highestId) {
            $query->where('id', '>', $highestId);
        }

        $collection = collect($currentCollection);
        $chunkSize = 100;
        $processed = 0;

        while ($collection->count() < 100) {
            $newModels = $query->skip($processed)->take($chunkSize)->get();

            if ($newModels->isEmpty()) {
                break;
            }

            $filtered = $newModels->filter(function ($pronunciation) use ($filenames, $listedIds) {
                return !in_array($pronunciation->translit, $filenames) && !$listedIds->contains($pronunciation->id);
            });

            $collection = $collection->merge($filtered);

            $processed += $chunkSize;
        }

        $pronunciations = $collection->take(100)->map(function ($pronunciation) {
            return array_merge($pronunciation->toArray(), [
                'term' => $pronunciation->term->term
            ]);
        })->toArray();

        return response()->json([
            'pronunciations' => $pronunciations,
        ]);
    }

    public function storeRecordings()
    {
        // posts the recordings to the Droplet.
    }

    public function upload(Request $request)
    {
        $file = $request->file('upload');
        $path = $file->store('uploads');

        return response()->json(['path' => $path]);
    }

    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        $user->preferences = $request->input('preferences');
        $user->save();

        return response()->json(['status' => 'success']);
    }

    public function stash(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:wav|max:5120'
        ]);

        try {
            $file = $request->file('file');
            $filename = uniqid('stash_') . '.' . $file->getClientOriginalExtension();

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

    public function finishUpload(Request $request)
    {
        // Handle metadata and finalize the record upload if necessary
        // This might include saving information about the file, speaker, etc., in your database

        $file = $request->file('file');
        $path = $file->store('digitalocean-stash', 'spaces');

        $localPath = public_path("stash/{$file->getClientOriginalName()}");
        if (File::exists($localPath)) {
            File::delete($localPath);
        }

//        return response()->json(['path' => $path], 200);

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully!'
        ]);
    }

    public function clearStash()
    {
        $path = public_path('stash');
        \Log::info('Clear stash method called. Path: ' . $path);

        if (File::isDirectory($path)) {
            \Log::info('Stash directory found. Cleaning up...');
            File::cleanDirectory($path);
            return response()->json(['message' => 'Stash directory cleaned up successfully.'], 200);
        }

        \Log::warning('Stash directory not found.');
        return response()->json(['message' => 'Stash directory not found.'], 404);
    }
}
