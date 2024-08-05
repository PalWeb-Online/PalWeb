@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel" class="with-feature">
        <a href="{{ route('wiki.show', 'release-notes') }}" class="feature-callout">
            v1.0 Release Notes ->
        </a>

        <h2>Welcome to PalWeb!</h2>
        <div class="hero-blurb">database-powered Palestinian Arabic learning tools</div>
    </div>

    <div class="feature-panel" style="background: none; padding: 0 1.6rem;">
        <x-deck :deck="\App\Models\Deck::find(56)"/>
    </div>

    <div class="feature-panel-wrapper">
        <img class="popout" src="{{ asset('/img/watermelon.svg') }}" alt="watermelon"/>

        <div class="feature-panel">
            <div class="feature-panel-content">
                <div class="feature-panel-subtitle" style="text-align: center; font-size: 3.2rem">Are you ready for some
                    next-level
                    Arabic?
                </div>
            </div>
        </div>

        <div class="feature-panel" style="flex-direction: row-reverse">
            <div class="feature-panel-content">
                <div class="feature-panel-title">{{ __('dictionary') }}</div>
                <div class="feature-panel-subtitle">Over {{ floor(\App\Models\Term::count() / 100) * 100 }} terms & counting.</div>
                <div class="feature-panel-description">Built off the knowledge of several other dictionaries, with some
                    home-grown research & computer-science magic, the PalWeb Dictionary is the only interactive Spoken
                    Arabic dictionary with hypertext pages that let you easily jump between terms, a powerful
                    English-or-Arabic search with finely tuned filters — & pronunciation samples of almost everything!
                </div>
            </div>
            <div class="feature-panel-feature">
                <div class="terms-list">
                    <x-term :term="\App\Models\Term::firstWhere('slug', 'noun-l-ʔuds')"/>
                    <x-term :term="\App\Models\Term::firstWhere('slug', 'preposition-b-')"/>
                    <x-term :term="\App\Models\Term::firstWhere('slug', 'noun-falasṭīn')"/>
                </div>
            </div>
        </div>

        <div class="feature-panel">
            <div class="feature-panel-content">
                <div class="feature-panel-title">{{ __('deck-builder') }}</div>
                <div class="feature-panel-subtitle">Build your own flashcard decks.</div>
                <div class="feature-panel-description">Say goodbye to the tedium of piecing together your own flashcards
                    from bits of dubious information. Collect terms into your own custom Decks & export them to your
                    favorite flashcard application! Or, if you need some inspiration, browse the Deck Library to see all
                    the Decks others have made; pin them, export them, or copy them to put your own spin on their idea.
                </div>
            </div>
            <div class="feature-panel-feature">
                <div style="display: flex; flex-flow: row wrap; justify-content: space-around; gap: 1.6rem">
                    <x-deck-flashcard :deck="\App\Models\Deck::find(1)"/>
                    <x-deck-flashcard :deck="\App\Models\Deck::find(2)"/>
                </div>
            </div>
        </div>

        <div class="feature-panel" style="flex-direction: row-reverse">
            <div class="feature-panel-content">
                <div class="feature-panel-title">{{ __('phrasebook') }}</div>
                <div class="feature-panel-subtitle">See words in their context.</div>
                <div class="feature-panel-description">Enough of clunky definitions that leave you unsure of how to
                    actually use a term in a sentence. Search for a term in the Phrasebook to see all the sentences in
                    the database that it appears in. View a sentence to see a list of all the terms it contains, with
                    their meanings. Stumped by another term? Click on it to quickly jump to its page in the Dictionary!
                </div>
            </div>
            <div class="feature-panel-feature">
                <x-sentence size="l" :sentence="\App\Models\Sentence::find(4)"/>
                <x-sentence size="l" :sentence="\App\Models\Sentence::find(5)"/>
            </div>
        </div>

        <div class="feature-panel" style="flex-direction: column; align-items: stretch">
            <div class="feature-panel-content">
                <div class="feature-panel-title">{{ __('community') }}</div>
                <div class="feature-panel-subtitle">Connect & share with others.</div>
            </div>
            <div class="feature-panel-feature">
                @include('users._profile', ['user' => \App\Models\User::find(1)])
            </div>
        </div>

        <div class="feature-panel">
            <div class="feature-panel-content">
                <div class="feature-panel-subtitle" style="text-align: center; font-size: 3.2rem">... & lots more!</div>
            </div>
        </div>
    </div>

@endsection

@section('content')
    <div class="portal-button-wrapper">
        <div class="portal-button-head">
            ready to get started?
        </div>
        <div class="portal-button-body">
            <a href="{{ route('signup') }}" class="portal-button">Create Your Account!</a>
        </div>
    </div>
@endsection
