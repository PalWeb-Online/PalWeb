<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use Illuminate\Http\Request;

class AudioController extends Controller
{
    public function index()
    {
        return Audio::all();
    }

    public function destroy(Audio $audio)
    {
//        TODO: Delete file from cloud server.

        $audio->delete();

        return response()->json([
            'success' => true,
            'message' => 'Audio deleted successfully.'
        ]);
    }
}
