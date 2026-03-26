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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct(protected FlasherInterface $flasher) {}

    public function show(User $user, Request $request): \Inertia\Response
    {
        Gate::authorize('interact', $user);

        $filters = array_merge(['sort' => 'latest'], $request->only(['sort',]));

        $user->load(['dialect', 'badges', 'speaker']);

        $speaker = $user->speaker?->load(['dialect'])->loadCount(['audios']);

        $sort = $request->string('sort')->toString();

        $decks = $user->decks()
            ->when($sort === 'alphabetical', fn ($query) => $query->orderBy('name'))
            ->when($sort !== 'alphabetical', fn ($query) => $query->orderByDesc('id'))
            ->paginate(25)
            ->withQueryString();

        return Inertia::render('Community/Users/Show', [
            'section' => 'community',
            'filters' => $filters,
            'user' => new UserResource($user),
            'decks' => DeckResource::collection($decks),
            'badges' => Badge::all(),
            'speaker' => $speaker ? new SpeakerResource($speaker) : null,
        ]);
    }

    public function edit(User $user): \Inertia\Response
    {
        Gate::authorize('modify', $user);

        $user->load(['dialect']);

        return Inertia::render('Community/Users/Edit', [
            'section' => 'community',
            'user' => new UserResource($user),
        ]);
    }

    public function update(User $user, UpdateUserRequest $request, FlasherInterface $flasher): RedirectResponse
    {
        Gate::authorize('modify', $user);

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

        session()->flash('notification',
            ['type' => 'success', 'message' => __('updated', ['thing' => 'your Profile'])]);

        return to_route('users.show', $user);
    }

    public function updatePreferences(Request $request): JsonResponse
    {
        $user = auth()->user();

        $request->validate([
            'srs_settings' => 'required|array',
            'srs_settings.new_limit' => 'integer|min:0|max:100',
            'srs_settings.review_limit' => 'integer|min:0|max:300',
            'srs_settings.learning_steps' => 'integer|min:1|max:5',
            'srs_settings.prompt_type' => 'string',
        ]);

        $preferences = $user->preferences ?? [];
        $preferences['srs'] = array_merge($preferences['srs'] ?? [], $request->input('srs_settings'));

        $user->preferences = $preferences;
        $user->save();

        return response()->json([
            'success' => true,
            'preferences' => $user->preferences
        ]);
    }

    public function getDecks(): JsonResponse
    {
        return response()->json([
            'decks' => DeckResource::collection(auth()->user()->decks->load(['terms'])),
        ]);
    }

    public function toggleView(Request $request, string $role = 'student')
    {
        if (!$request->user()->isSuperuser()) {
            abort(403);
        }

        if (session()->has('view_as_role')) {
            session()->forget('view_as_role');

        } else {
            session()->put('view_as_role', $role);
        }

        return back();
    }
}
