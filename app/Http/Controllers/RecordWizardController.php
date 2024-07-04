<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecordWizardController extends Controller
{
    public function index() {
        return view('record.index');
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
}
