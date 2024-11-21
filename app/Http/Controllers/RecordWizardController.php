<?php

namespace App\Http\Controllers;

use App\Models\Dialect;
use App\Models\Location;
use App\Models\Pronunciation;
use App\Models\Speaker;
use Illuminate\Http\Request;
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

    // Example controller method for processing file uploads
    public function upload(Request $request)
    {
        $file = $request->file('upload');
        $path = $file->store('uploads');

        // If processing WebM files with FFmpeg is required
        if ($file->getClientMimeType() == 'video/webm') {
            $ffmpegLocation = config('app.ffmpeg_location'); // Set FFmpeg location in config
            $tempPath = storage_path('app/'.$path);
            shell_exec("{$ffmpegLocation} -i {$tempPath} -c copy {$tempPath}-ffmpeg.webm");
            rename("{$tempPath}-ffmpeg.webm", "{$tempPath}");
        }

        return response()->json(['path' => $path]);
    }

    // Example method to update user preferences
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

            $path = $file->storeAs('stash', $filename);

            return response()->json([
                'message' => 'File stashed successfully.',
                'stashkey' => $filename,
                'url' => Storage::url($path),
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

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully!'
        ]);
    }
}
