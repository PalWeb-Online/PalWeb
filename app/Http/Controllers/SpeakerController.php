<?php

namespace App\Http\Controllers;

use App\Models\Dialect;
use App\Models\Location;
use App\Models\Speaker;
use Illuminate\Http\Request;

class SpeakerController extends Controller
{
    public function getSpeaker()
    {
        $user = auth()->user();

        $speaker = Speaker::firstWhere('user_id', $user->id) ?? [
            'user_id' => $user->id,
            'dialect_id' => null,
            'location_id' => null,
            'fluency' => '',
            'gender' => '',
        ];

        return response()->json([
            'speaker' => $speaker,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'exists' => Speaker::where('user_id', $user->id)->exists()
        ]);
    }

    public function getSpeakerOptions()
    {
        return response()->json([
            'dialects' => Dialect::all()->toArray(),
            'locations' => Location::all()->makeHidden('coordinates')->toArray(),
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
                'fluency' => $request->input('fluency'),
                'gender' => $request->input('gender')
            ]
        );

        $user->update(['speaker_id' => $speaker->id]);
        $user->refresh();

        return response()->json([
            'speaker' => $speaker,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'message' => 'Your Speaker profile has been saved successfully.',
        ]);
    }
}
