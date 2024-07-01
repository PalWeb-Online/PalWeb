<div class="badges-container">
    <div class="featured-title l">Badges</div>
    <div class="badge-wrapper">
        @foreach($badges as $badge)
            <div class="badge {{ $user->badges->contains($badge->id) ? 'enabled' : 'disabled' }}">
                <img data-tippy-info
                     data-tippy-content="{{ $user->badges->contains($badge->id) ? $badge->name : '???'}}: {{ $badge->description }}"
                     alt="{{ $badge->name }}"
                     src="{{ asset('img/badges/'.$badge->image) }}"/>
            </div>
        @endforeach
    </div>

    <img class="popout" src="{{ asset('img/star.svg') }}" alt="star"/>
</div>
