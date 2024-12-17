<div class="user-portrait-wrapper">
    <a href="{{ route('users.show', $user) }}" class="user-avatar">
        <img alt="Profile Picture"
             src="{{ asset('img/avatars/' . $user->avatar) }}"/>
    </a>
    <div class="user-portrait-name">
        {{ $user->ar_name }}
    </div>

    <div class="user-creations-count">
{{--        todo: where not private--}}
        {{ $user->decks->count() }}
        ·
        {{ $user->speaker ? $user->speaker->audios->count() : '0' }}
    </div>
</div>
