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
            <x-link :href="route('dialogs.index')">{{ __('dialogs') }}</x-link>
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
            <a href="{{ route('terms.random') }}">{{ __('terms.random') }}</a>
            <a href="{{ route('missing.terms.create') }}">{{ __('missing.terms.create') }}</a>
        </x-slot>
    </x-dropdown>
    <x-dropdown>
        <x-slot name="trigger">
            <x-link :href="route('community.index')"
                    :active="request()->routeIs('community.index', 'users.show', 'decks.index', 'decks.show')">{{ __('community') }}
            </x-link>
        </x-slot>
        <x-slot name="content">
            <div class="dropdown-section-title">Portals</div>
            <a href="{{ route('decks.index') }}">Decks</a>
            <a href="{{ route('audios.index') }}">Audios</a>

            <div class="dropdown-section-title">Tools</div>
            <a href="{{ route('decks.create') }}">Deck Builder</a>
            <a href="{{ route('audios.record') }}">Record Wizard</a>
        </x-slot>
    </x-dropdown>
    <x-dropdown>
        <x-slot name="trigger">
            <x-link :href="route('wiki.index')"
                    :active="request()->routeIs('wiki.index', 'wiki.show')">{{ __('wiki') }}</x-link>
        </x-slot>
        <x-slot name="content">
            <div class="dropdown-section-title">Welcome</div>
            <a href="{{ route('wiki.show', 'about') }}">About</a>
            <a href="{{ route('wiki.show', 'user-guide') }}">User Guide</a>

            <div class="dropdown-section-title">Support</div>
            <a href="{{ route('subscription.index') }}">Subscribe</a>
            <a href="https://www.ko-fi.com/palweb" target="_blank">Ko-fi</a>
        </x-slot>
    </x-dropdown>
</div>
