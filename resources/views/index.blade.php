@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel" class="with-feature">
        <a href="{{ route('wiki.show', 'release-notes') }}" class="feature-callout">
            v1.2 Release Notes ->
        </a>

        <div style="display: flex; flex-flow: column; align-items: center; gap: 4.8rem; margin-block: 3.2rem">
            <div style="display: grid; justify-items: start">
                <h2>welcome to</h2>
                <h1>PalWeb</h1>
            </div>

            <div class="hero-blurb">
                <span id="typed-element"></span>
            </div>
        </div>
    </div>

    <div class="feature-panel-wrapper" style="background: none; padding: 0 1.6rem;">
        <x-deck-container :deck="\App\Models\Deck::find(56)"/>

        <div class="model-counter-grid">
            <div class="model-counter">
                <div class="model-counter-count">{{ \App\Models\Term::count() }}</div>
                <div class="model-counter-body">
                    <span class="model-counter-model">Terms</span>
                    <span class="model-counter-description">in the Dictionary</span>
                </div>
            </div>
            <div class="model-counter">
                <div class="model-counter-count">{{ \App\Models\Sentence::count() }}</div>
                <div class="model-counter-body">
                    <span class="model-counter-model">Sentences</span>
                    <span class="model-counter-description">in the Phrasebook</span>
                </div>
            </div>
        </div>

        <div class="feature-panel-feature">
            <x-sentence-item size="l" :sentence="\App\Models\Sentence::find(255)"/>
            <x-sentence-item size="l" :sentence="\App\Models\Sentence::find(256)"/>
            <x-sentence-item size="l" :sentence="\App\Models\Sentence::find(257)"/>
        </div>
    </div>

    <div class="feature-panel-wrapper" style="">
        <img class="popout" src="{{ asset('/img/watermelon.svg') }}" alt="watermelon"/>

        <div class="feature-panel">
            <div class="feature-panel-content">
                <div class="feature-panel-title">flashcard decks</div>
                <div class="feature-panel-subtitle">Build your vocabulary. Build Decks.</div>
                <div class="feature-panel-description">Say goodbye to the tedium of piecing together your own vocabulary
                    sets from bits of dubious information. Use the power of the PalWeb Dictionary to build your own
                    custom Flashcard Decks in a flash. If you need some inspiration, browse the Deck Library to see
                    all the Decks others have made — & even copy a Deck to put your own spin on someone else's idea!
                    Export your beautiful creations if you'd like to use them in third-party flashcard applications, or
                    — better yet — study them right here using the interactive Flashcard Portal!
                </div>
            </div>

            <div class="feature-panel-feature">
                <div style="display: flex; flex-flow: row wrap; justify-content: space-around; gap: 1.6rem">
                    <x-vue.deck component="DeckFlashcard" :deck="\App\Models\Deck::find(1)"/>
                    <x-vue.deck component="DeckFlashcard" :deck="\App\Models\Deck::find(83)"/>
                    <x-vue.deck component="DeckFlashcard" :deck="\App\Models\Deck::find(100)"/>
                </div>
            </div>
        </div>

        <div>
            <img src="{{ asset('img/globe-america.svg') }}" class="world" alt="America"/>
            <img src="{{ asset('img/globe-africa.svg') }}" class="world" alt="Africa"/>
            <img src="{{ asset('img/globe-asia.svg') }}" class="world" alt="Asia"/>
        </div>

        <div class="feature-panel" style="margin-block-end: -25.6rem">
            <div class="feature-panel-content">
                <div class="feature-panel-title">building community</div>
                <div class="feature-panel-subtitle">Connect & share with others.</div>
            </div>
            <div class="feature-panel-feature">
                @include('users._profile', ['user' => \App\Models\User::find(1)])
            </div>
        </div>
    </div>

    <div class="feature-panel-wrapper" style="background: none; padding: 0 1.6rem; margin-block-start: 19.2rem">
        <div class="model-counter-grid">
            <div class="model-counter">
                <div class="model-counter-count">{{ \App\Models\Deck::count() }}</div>
                <div class="model-counter-body">
                    <span class="model-counter-model">Decks</span>
                    <span class="model-counter-description">in the Library</span>
                </div>
            </div>
            <div class="model-counter">
                <div class="model-counter-count">{{ \App\Models\Audio::count() }}</div>
                <div class="model-counter-body">
                    <span class="model-counter-model">Audios</span>
                    <span class="model-counter-description">in the Library</span>
                </div>
            </div>
            <div class="model-counter">
                <div class="model-counter-count">{{ \App\Models\User::count() }}</div>
                <div class="model-counter-body">
                    <span class="model-counter-model">Pals</span>
                    <span class="model-counter-description">in the Community</span>
                </div>
            </div>
        </div>

        <div class="portal-button-wrapper">
            <div class="portal-button-head">
                ready to get started?
            </div>
            <div class="portal-button-body">
                <a href="{{ route('signup') }}" class="portal-button">Create Your Account!</a>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
    <script>
        var typed = new Typed('#typed-element', {
            strings: ['database-powered Palestinian Arabic learning tools.'],
            typeSpeed: 50,
        });
    </script>
@endsection
