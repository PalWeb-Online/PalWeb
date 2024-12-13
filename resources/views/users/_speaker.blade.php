<div class="speaker-profile-container">
    <div class="speaker-profile-head">
        <div>Speaker</div>
        @if(!request()->routeIs('users.show'))
            @unless($speaker->user->private)
                <a class="mini-user-profile" href="{{ route('users.show', $speaker->user) }}">
                    <div>{{ $speaker->user->name }} ({{ $speaker->user->username }})</div>
                    <img alt="User Avatar" src="{{ asset('img/avatars/'.$speaker->user->avatar) }}"/>
                </a>
            @else
                <div style="padding-inline: 1.6rem">Speaker #{{ $speaker->id }}</div>
            @endunless
        @endif
    </div>

    <div class="speaker-profile-body">
        <div class="speaker-profile-row">
            <div>Dialect</div>
            <div>{{ $speaker->dialect->name }}</div>
        </div>
        <div class="speaker-profile-row">
            <div>Location</div>
            <div>{{ $speaker->location->name }}</div>
        </div>
        <div class="speaker-profile-row">
            <div>Gender</div>
            <div>{{ $speaker->gender }}</div>
        </div>
        <div class="speaker-profile-row">
            <div>Fluency</div>
            <div>{{ $speaker->fluency }}</div>
        </div>
    </div>

    @if(!request()->routeIs('audios.speaker'))
        <div class="speaker-profile-row">
            <div>Audios</div>
            <div>
                {{--                TODO: link should be underlined--}}
                <div>
                    {{ count($speaker->audios) }}
                    (<a href="{{ route('audios.speaker', $speaker) }}">See All</a>)
                </div>
            </div>
        </div>
    @endif
</div>
