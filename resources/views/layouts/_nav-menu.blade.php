<div class="nav-menu">
    <x-dropdown>
        <x-slot name="trigger">
            <x-link :href="route('academy.index')"
                    :active="request()->routeIs('academy.index', 'academy.unit', 'academy.lesson')">{{ __('academy') }}
            </x-link>
        </x-slot>
        <x-slot name="content">
            <div class="dropdown-section-title">Portals</div>
            <x-link :href="route('academy.index')">{{ __('academy') }}</x-link>
            <x-link :href="route('texts.index')">{{ __('texts') }}</x-link>
        </x-slot>
    </x-dropdown>
    <x-dropdown>
        <x-slot name="trigger">
            <x-link :href="route('terms.index')"
                    :active="request()->routeIs('terms.index', 'terms.show', 'sentences.index', 'sentences.show', 'explore.index', 'explore.page')">{{ __('dictionary') }}
            </x-link>
        </x-slot>
        <x-slot name="content">
            <div class="dropdown-section-title">Portals</div>
            <a href="{{ route('terms.index') }}">{{ __('dictionary') }}</a>
            <a href="{{ route('sentences.index') }}">{{ __('phrasebook') }}</a>
            <a href="{{ route('explore.index') }}">{{ __('explore') }}</a>

            <div class="dropdown-section-title">Tools</div>
            <a href="{{ route('wiki.show', 'dictionary') }}">User Manual</a>
            <a href="{{ route('terms.random') }}">{{ __('terms.random') }}</a>
            <a href="{{ route('terms.request') }}">{{ __('terms.request') }}</a>
        </x-slot>
    </x-dropdown>
    <x-dropdown>
        <x-slot name="trigger">
            <x-link :href="route('decks.index')"
                    :active="request()->routeIs('community.index', 'users.show', 'decks.index', 'decks.show')">{{ __('community') }}
            </x-link>
        </x-slot>
        <x-slot name="content">
            <div class="dropdown-section-title">Share</div>
            <a href="{{ route('decks.index') }}">Deck Library</a>
            <a href="{{ route('decks.create') }}">Deck Builder</a>

            <div class="dropdown-section-title">Support</div>
            <a href="{{ route('wiki.show', 'contributing') }}">Contributing</a>
            <a href="{{ route('dashboard.subscription') }}">Subscribe</a>
            <a href="https://www.ko-fi.com/palweb" target="_blank">Ko-fi</a>
        </x-slot>
    </x-dropdown>
    <x-link :href="route('wiki.index')"
            :active="request()->routeIs('wiki.index', 'wiki.show')">{{ __('wiki') }}</x-link>
</div>
