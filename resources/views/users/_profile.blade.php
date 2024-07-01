<div class="user-container">
    <div class="user-wrapper">
        <div class="user-avatar">
            @if(auth()->user() && $user->id === auth()->user()->id)
                <a href="{{ route('settings.avatar.edit') }}" class="material-symbols-rounded">edit</a>
            @endif
            <img alt="Profile Picture"
                 src="{{ asset('img/avatars/'.$user->avatar) }}"/>
        </div>
        <div class="user-comment">
            <div class="user-comment-head">
                <div>{{ $user->name }}</div>
                <div>({{ $user->username }})</div>
            </div>
            <div class="user-comment-body">
                <div class="user-comment-body-content">
                    @isset($user->bio)
                        {{ $user->bio }}
                    @else
                        <i>Sadly, {{ $user->name }} hasn't told us anything about themselves yet.</i>
                    @endisset
                </div>
                <div class="user-comment-body-data">
                    Joined on {{ $user->created_at->format('j F Y') }} ({{ $user->created_at->diffForHumans() }}).
                </div>
            </div>

            <div class="user-comment-tag-wrapper">
                <div class="user-comment-tag">
                    <img class="location" src="{{ asset('img/location.svg') }}" alt="location"/>
                    @isset($user->home)
                        {{ $user->home }}
                    @else
                        Earth, probably.
                    @endisset
                </div>

                <div class="user-comment-tag">
                    <img class="dialect" src="{{ asset('img/mouth.svg') }}" alt="dialect"/>
                    {{ $user->dialect->name }}
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user() && $user->id === auth()->user()->id)
        @if($user->private)
            <img class="profile-privacy" src="{{ asset('img/lock.svg') }}" alt="location" data-tippy-info
                 data-tippy-content="Your Profile is set to Private."/>
        @else
            <img class="profile-privacy" src="{{ asset('img/public.svg') }}" alt="location"
                 data-tippy-info
                 data-tippy-content="Your Profile is set to Public."/>
        @endif

        <x-context-actions>
            <a href="{{ route('settings.profile.edit') }}">Edit Profile</a>
            <form method="POST" action="{{ route('settings.privacy.toggle', $user->id) }}">
                @method('PATCH')
                @csrf
                <button>
                    {{ $user->private ? __('Make Public') : __('Make Private') }}
                </button>
            </form>
        </x-context-actions>
    @endif
</div>
