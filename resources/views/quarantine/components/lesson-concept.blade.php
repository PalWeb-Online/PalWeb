<div class="lesson-concept-container {{ $sectionType }}">
    @if($sectionType === 'skill')
        <img class="popout" src="{{ asset('img/pencil.svg') }}" alt="idea"/>
    @else
        <img class="popout" src="{{ asset('img/idea.svg') }}" alt="idea"/>
    @endif

    <div class="lesson-concept-head">
        <div class="featured-title s">{{ __($sectionType) }}: {{ $sectionTitle }}</div>
    </div>

    <div class="lesson-concept-body">
        <h1>{{ $title }}</h1>
        {{ $slot }}
    </div>
</div>
