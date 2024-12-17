@props([
    'title',
    'blurb' => false,
    'img',
])

<div class="portal-section">
    <div class="portal-section-head">
        <div class="portal-section-title">
            <img src="{{ asset('img/'. $img ) }}" alt="Icon"/>
            <div>{{ $title }}</div>
        </div>

        {{ $slot }}
    </div>

    @if($blurb)
        <div class="portal-section-blurb">
            {{ $blurb }}
        </div>
    @endif
</div>
