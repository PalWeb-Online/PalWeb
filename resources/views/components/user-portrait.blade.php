@props([
    'user',
    'size' => 's',
    'creations' => false
])

<div class="user-portrait-wrapper {{ $size }}">
    <a href="{{ route('users.show', $user) }}" class="user-avatar">
        <img alt="Profile Picture"
             src="{{ asset('img/avatars/' . $user->avatar) }}"/>
    </a>
    <div class="user-portrait-name">
        {{ $user->name }}
    </div>

    @if($creations)
        <div class="user-creations-count">
            {{ $user->decks->where('private', false)->count() }}
            ·
            {{ $user->speaker ? $user->speaker->audios->count() : '0' }}
        </div>
    @endif
</div>
