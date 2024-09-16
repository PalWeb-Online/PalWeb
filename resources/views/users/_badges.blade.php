<div class="badges-container">
    <div class="featured-title l">Badges</div>
    <div class="badge-wrapper">
        @foreach($badges as $badge)
            <x-vue.badge :badge="$badge" :user="$user" />
        @endforeach
    </div>

    <img class="popout" src="{{ asset('img/star.svg') }}" alt="star"/>
</div>
