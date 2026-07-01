<?php

namespace App\Http\Controllers;

use App\Events\ProfileChanged;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\DeckResource;
use App\Http\Resources\SpeakerResource;
use App\Http\Resources\UserEditResource;
use App\Http\Resources\UserShowResource;
use App\Models\Avatar;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class UserController extends Controller
{
    public function show(User $user, Request $request): \Inertia\Response
    {
        Gate::authorize('interact', $user);

        $filters = array_merge(['sort' => 'latest'], $request->only(['sort',]));

        $user->load(['dialect', 'badges', 'speaker', 'teacher', 'roles']);

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
            'user' => new UserShowResource($user),
            'decks' => DeckResource::collection($decks),
            'badges' => Badge::all(),
            'speaker' => $speaker ? new SpeakerResource($speaker) : null,
        ]);
    }

    public function edit(User $user): \Inertia\Response
    {
        Gate::authorize('modify', $user);

        return Inertia::render('Community/Users/Edit', [
            'section' => 'community',
            'userId' => $user->id,
            'username' => $user->username,
        ]);
    }

    public function fetch(User $user): JsonResponse
    {
        Gate::authorize('modify', $user);

        $user->load(['dialect', 'teacher', 'roles', 'uploadedAvatars']);

        return response()->json([
            'user' => new UserEditResource($user),
        ]);
    }

    public function update(User $user, UpdateUserRequest $request): JsonResponse|RedirectResponse
    {
        Gate::authorize('modify', $user);

        $selectedAvatarId = $this->resolveAvatarSelection($user, $request);

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'ar_name' => $request->ar_name,
            'home' => $request->home,
            'bio' => $request->bio,
            'avatar' => $request->avatar,
            'avatar_id' => $selectedAvatarId,
            'private' => $request->private,
            'dialect_id' => $request->dialect_id,
        ]);

        event(new ProfileChanged($user));

        if ($request->expectsJson()) {
            return response()->json([
                'user' => new UserEditResource($user->fresh(['dialect', 'teacher', 'uploadedAvatars'])),
            ]);
        }

        session()->flash('notification',
            ['type' => 'success', 'message' => __('updated', ['thing' => 'your Profile'])]);

        return to_route('users.show', $user);
    }

    private function resolveAvatarSelection(User $user, UpdateUserRequest $request): ?int
    {
        if (Avatar::whereKey($request->integer('avatar_id'))->exists()) {
            Gate::authorize('select', [Avatar::class, $user]);

            $avatar = $user->uploadedAvatars()
                ->whereKey($request->integer('avatar_id'))
                ->first();

            if (! $avatar) {
                abort(422, 'The selected Avatar does not belong to this user.');
            }

            return $avatar->id;
        }

        return null;
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

    public function toggleStudentRole(User $user): JsonResponse
    {
        $user->hasRole('student')
            ? $user->revokeStudentRole()
            : $user->grantStudentRole();

        $user->load('roles');

        return response()->json([
            'success' => true,
            'user' => new UserShowResource($user)
        ]);
    }

    public function toggleView(Request $request, string $role = 'student')
    {
        if (! $request->user()->isSuperuser()) {
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
