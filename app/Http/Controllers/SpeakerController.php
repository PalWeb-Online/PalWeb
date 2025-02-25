<?php

namespace App\Http\Controllers;

use App\Models\Dialect;
use App\Models\Location;
use App\Models\Speaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SpeakerController extends Controller
{
    public function show(Speaker $speaker): \Illuminate\View\View
    {
        $audios = $speaker->audios()
            ->with('speaker')
            ->orderByDesc('id')
            ->paginate(25)
            ->onEachSide(1);

        View::share('pageTitle', 'Speaker: '.$speaker->user->username);

        return view('community.audios.speaker', [
            'user' => $speaker->user,
            'speaker' => $speaker,
            'audios' => $audios,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        $speaker = Speaker::updateOrCreate(
            ['user_id' => $user->id],
            [
                'dialect_id' => $request->input('dialect_id'),
                'location_id' => $request->input('location_id'),
                'fluency' => $request->input('fluency'),
                'gender' => $request->input('gender'),
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

    public function getSpeaker(Request $request): JsonResponse
    {
        $user = $request->user();

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
            'exists' => Speaker::where('user_id', $user->id)->exists(),
        ]);
    }

    public function getSpeakerOptions(): JsonResponse
    {
        return response()->json([
            'dialects' => Dialect::find([6, 7, 8, 9, 10, 11, 12])->toArray(),
            'locations' => Location::all()->makeHidden('coordinates')->toArray(),
        ]);
    }
}
