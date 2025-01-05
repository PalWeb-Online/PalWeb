<nav class="nav-mobile">
    <a class="logo" href="{{ route('homepage') }}">
        <img src="{{ asset('/img/logo.svg') }}" alt="PalWeb Logo"/>
    </a>

{{--    <button class="sg-trigger" onclick="window.dispatchEvent(new CustomEvent('open-search-genie'))">--}}
{{--        <img src="{{ asset('img/search.svg') }}" alt="Search"/>--}}
{{--    </button>--}}

    <div class="nav-mobile-main">
        <div class="nav-mobile-section">
            <x-link :href="route('academy.index')"
                    :active="request()->routeIs('academy.index', 'academy.unit', 'academy.lesson')">{{ __('academy') }}</x-link>
            <x-link :href="route('texts.index')"
                    :active="request()->routeIs('texts.index', 'texts.show')">{{ __('texts') }}</x-link>
        </div>

        <div class="nav-mobile-section">
            <x-link :href="route('terms.index')"
                    :active="request()->routeIs('terms.index', 'terms.show')">{{ __('dictionary') }}</x-link>
            <x-link :href="route('sentences.index')"
                    :active="request()->routeIs('sentences.index', 'sentences.show')">{{ __('phrasebook') }}</x-link>
            <x-link :href="route('explore.index')"
                    :active="request()->routeIs('explore.index', 'explore.show')">{{ __('explore') }}</x-link>
            <a href="{{ route('terms.random') }}">{{ __('random') }}</a>
        </div>

        <div class="nav-mobile-section">
            <x-link :href="route('community.index')"
                    :active="request()->routeIs('community.index')">{{ __('community') }}</x-link>
            <x-link :href="route('decks.index')"
                    :active="request()->routeIs('decks.index', 'decks.show')">{{ __('decks') }}</x-link>
            <x-link :href="route('audios.index')"
                    :active="request()->routeIs('audios.index')">{{ __('audios') }}</x-link>
        </div>

        <div class="nav-mobile-section">
            <x-link :href="route('wiki.index')"
                    :active="request()->routeIs('wiki.index', 'wiki.show')">{{ __('wiki') }}</x-link>
            <x-link :href="route('wiki.show', 'user-guide')">{{ __('user guide') }}</x-link>
        </div>
    </div>

    @if(request()->routeIs('wiki.index', 'wiki.show'))
        @include('layouts._nav-wiki')
    @endif
</nav>

<button class="nav-mobile-toggle">
    <img src="{{ asset('img/compass.svg') }}" alt="compass"/>
</button>
