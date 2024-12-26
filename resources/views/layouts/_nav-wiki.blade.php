<div class="nav-wiki">
    <x-link :href="route('wiki.show', 'release-notes')"
            :active="url()->current() == route('wiki.show', 'release-notes')">v1.2 Release Notes
    </x-link>

    <x-link :href="route('wiki.show', 'faq')"
            :active="url()->current() == route('wiki.show', 'faq')">FAQ
    </x-link>
    <x-link :href="route('wiki.show', 'about')"
            :active="url()->current() == route('wiki.show', 'about')">About
    </x-link>

    <div class="nav-wiki-section">
        <a>the Website</a>
        <x-link :href="route('wiki.show', 'academy')"
                :active="url()->current() == route('wiki.show', 'academy')">Academy
        </x-link>
        <x-link :href="route('wiki.show', 'dictionary')"
                :active="url()->current() == route('wiki.show', 'dictionary')">Dictionary
        </x-link>
        <x-link :href="route('wiki.show', 'workbench')"
                :active="url()->current() == route('wiki.show', 'workbench')">Workbench
        </x-link>
        <x-link :href="route('wiki.show', 'contributing')"
                :active="url()->current() == route('wiki.show', 'contributing')">Contributing
        </x-link>
    </div>

    <div class="nav-wiki-section">
        <a>the Language</a>
        <x-link :href="route('wiki.show', 'ajp')"
                :active="url()->current() == route('wiki.show', 'ajp')">Palestinian Arabic
        </x-link>
        <x-link :href="route('wiki.show', 'dialects')"
                :active="url()->current() == route('wiki.show', 'dialects')">Dialects
        </x-link>
    </div>

    <div class="nav-wiki-section">
        <x-link :href="route('wiki.show', 'phonology')"
                :active="url()->current() == route('wiki.show', 'phonology')">AJP: Phonology
        </x-link>
        <x-link :href="route('wiki.show', 'vowels')"
                :active="url()->current() == route('wiki.show', 'vowels')">{{ __('vowels') }}</x-link>
        <x-link :href="route('wiki.show', 'consonants')"
                :active="url()->current() == route('wiki.show', 'consonants')">Consonants
        </x-link>
        <a>Syllables & Stress</a>
        <a>Phonotactics</a>
    </div>

    <div class="nav-wiki-section">
        <a>AJP: Morphology</a>
        <x-link :href="route('wiki.show', 'roots')"
                :active="url()->current() == route('wiki.show', 'roots')">{{ __('roots') }}</x-link>
        <x-link :href="route('wiki.show', 'patterns')"
                :active="url()->current() == route('wiki.show', 'patterns')">{{ __('patterns') }}</x-link>
        <x-link :href="route('wiki.show', 'verb-forms')"
                :active="url()->current() == route('wiki.show', 'verb-forms')">Verb Forms
        </x-link>
        <x-link :href="route('wiki.show', 'affixes')"
                :active="url()->current() == route('wiki.show', 'affixes')">{{ __('affixes') }}</x-link>
    </div>

    <div class="nav-wiki-section">
        <x-link :href="route('wiki.show', 'syntax')"
                :active="url()->current() == route('wiki.show', 'syntax')">AJP: Syntax
        </x-link>
        <x-link :href="route('wiki.show', 'verbs')"
                :active="url()->current() == route('wiki.show', 'verbs')">{{ __('verbs') }}</x-link>
        <x-link :href="route('wiki.show', 'nouns')"
                :active="url()->current() == route('wiki.show', 'nouns')">{{ __('nouns') }}</x-link>
        <x-link :href="route('wiki.show', 'adjectives')"
                :active="url()->current() == route('wiki.show', 'adjectives')">{{ __('adjectives') }}</x-link>
        <x-link :href="route('wiki.show', 'numerals')"
                :active="url()->current() == route('wiki.show', 'numerals')">{{ __('numerals') }}</x-link>
        <x-link :href="route('wiki.show', 'adverbs')"
                :active="url()->current() == route('wiki.show', 'adverbs')">{{ __('adverbs') }}</x-link>
        <x-link :href="route('wiki.show', 'prepositions')"
                :active="url()->current() == route('wiki.show', 'prepositions')">{{ __('prepositions') }}</x-link>
        <x-link :href="route('wiki.show', 'conjunctions')"
                :active="url()->current() == route('wiki.show', 'conjunctions')">{{ __('conjunctions') }}</x-link>
        <x-link :href="route('wiki.show', 'determiners')"
                :active="url()->current() == route('wiki.show', 'determiners')">{{ __('determiners') }}</x-link>
        <x-link :href="route('wiki.show', 'particles')"
                :active="url()->current() == route('wiki.show', 'particles')">{{ __('particles') }}</x-link>
        <x-link :href="route('wiki.show', 'phrases')"
                :active="url()->current() == route('wiki.show', 'phrases')">{{ __('phrases') }}</x-link>
        <a>Clause Types</a>
    </div>

    <img class="popout" src="{{ asset('img/olive.svg') }}" alt="olive"/>
</div>
