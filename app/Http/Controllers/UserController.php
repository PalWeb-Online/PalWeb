<?php

namespace App\Http\Controllers;

use App\Events\ProfileChanged;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\DeckResource;
use App\Http\Resources\SpeakerResource;
use App\Http\Resources\UserResource;
use App\Models\Badge;
use App\Models\User;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct(protected FlasherInterface $flasher)
    {
    }

    public function show(User $user): \Inertia\Response
    {
        $this->authorize('interact', $user);

        $user->load(['dialect', 'badges', 'speaker', 'decks']);

        $speaker = $user->speaker?->load(['dialect'])->loadCount(['audios']);

        return Inertia::render('Community/Users/Show', [
            'section' => 'community',
            'user' => new UserResource($user),
            'decks' => DeckResource::collection($user->decks),
            'badges' => Badge::all(),
            'speaker' => $speaker ? new SpeakerResource($speaker) : null,
        ]);
    }

    public function edit(User $user): \Inertia\Response
    {
        $this->authorize('modify', $user);

        $user->load(['dialect']);

        return Inertia::render('Community/Users/Edit', [
            'section' => 'community',
            'user' => new UserResource($user),
        ]);
    }

    public function update(User $user, UpdateUserRequest $request, FlasherInterface $flasher): RedirectResponse
    {
        $this->authorize('modify', $user);

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'ar_name' => $request->ar_name,
            'home' => $request->home,
            'bio' => $request->bio,
            'avatar' => $request->avatar,
            'private' => $request->private,
            'dialect_id' => $request->dialect_id,
        ]);

        event(new ProfileChanged($user));

        return to_route('users.show', $user);
    }

    public function getDecks(): JsonResponse
    {
        return response()->json([
            'decks' => DeckResource::collection(auth()->user()->decks->load(['terms'])),
        ]);
    }
}
