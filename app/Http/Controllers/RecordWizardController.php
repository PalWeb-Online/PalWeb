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
        $collection = collect(
            Pronunciation::whereIn('id', collect($listed)->pluck('id'))->get()
        );

        $files = Storage::disk('s3')->files('audio');
        $filenames = array_map(function ($file) {
            return pathinfo($file, PATHINFO_FILENAME);
        }, $files);

        $query = Pronunciation::where('dialect_id', $request->input('dialect_id'));
        $highestId = collect($listed)->pluck('id')->max();
        if ($highestId) {
            $query->where('id', '>', $highestId);
        }

        $chunkSize = 100;
        $processed = 0;

        while ($collection->count() < 100) {
            $models = $query->skip($processed)->take($chunkSize)->get();

            if ($models->isEmpty()) {
                break;
            }

            $filtered = $models->filter(function ($pronunciation) use ($filenames) {
                return !in_array($pronunciation->translit, $filenames);
            });

            $collection = $collection->merge($filtered);
            $processed += $chunkSize;
        }

        $pronunciations = $collection->take(100)->toArray();

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

    public function uploadToStash(Request $request)
    {
        // Validate the request
        $request->validate([
            'file' => 'required|file|mimes:wav,webm',  // Adjust based on your file types
        ]);

        // Handle the file upload to DigitalOcean Space (using the AWS SDK)
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();

        // Assuming you're using a disk configured for DigitalOcean Spaces in config/filesystems.php
        $path = Storage::disk('s3')->putFileAs('recordings', $file, $filename);

        // Get the file URL
        $url = Storage::disk('s3')->url($path);

        return response()->json([
            'success' => true,
            'stashkey' => $filename, // You can generate your own stash key if needed
            'url' => $url
        ]);
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
