<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Storage;

class RecordWizardController extends Controller
{
    public function index() {
        View::share('pageTitle', 'Record Wizard');

        return view('record.index', [
            'layout' => 'app'
        ]);
    }

    public function getSpeakers()
    {
        // passes all the Speaker models to the view.
    }

    public function storeSpeaker()
    {
        // creates a new Speaker or updates its data.
    }

    public function getPronunciations()
    {
        // loads the Terms & Sentences missing Pronunciations into memory & passes them to the index() function.
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
            $tempPath = storage_path('app/' . $path);
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
