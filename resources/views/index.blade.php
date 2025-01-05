@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel">
        <a href="{{ route('wiki.show', 'release-notes') }}" class="feature-callout">v1.2 Release Notes -></a>

        <h2>database-powered Palestinian Arabic learning tools</h2>
        <div class="hero-blurb">Learning a language means making it yours.<br/><span id="typed-element"></span></div>
    </div>

    <div class="feature-panel-wrapper transparent">
        <x-deck-container :deck="\App\Models\Deck::find(56)"/>

        <div class="feature-panel-content" style="text-align: center">
            <div class="feature-panel-title">language is a web</div>
            <div class="feature-panel-subtitle">PalWeb is the Web of Palestinian Arabic</div>
        </div>

        <div class="model-counter-wrapper">
            <div class="model-counter">
                <div class="model-counter-count">{{ \App\Models\Term::count() }}</div>
                <div class="model-counter-body">
                    <span class="model-counter-model">Terms</span>
                </div>
            </div>
            <div class="model-counter">
                <div class="model-counter-count">{{ \App\Models\Sentence::count() }}</div>
                <div class="model-counter-body">
                    <span class="model-counter-model">Sentences</span>
                </div>
            </div>
        </div>

        <div>
            <x-sentence-item size="l" :sentence="\App\Models\Sentence::find(255)"/>
            <x-sentence-item size="l" :sentence="\App\Models\Sentence::find(256)"/>
            <x-sentence-item size="l" :sentence="\App\Models\Sentence::find(257)"/>
        </div>
    </div>

    <div class="feature-panel-wrapper purple">
        <img class="popout" src="{{ asset('/img/watermelon.svg') }}" alt="watermelon"/>

        <div class="feature-panel-content">
            <div class="feature-panel-subtitle">hassle-free language learning is here.</div>
        </div>

        <div class="feature-panel inline">
            <div class="feature-panel-content">
                <div class="feature-panel-title">deck builder</div>
                <div class="feature-panel-subtitle">Build your vocabulary. Build Decks.</div>
                <div class="feature-panel-description">Say goodbye to the tedium of piecing together your own vocabulary
                    sets from bits of dubious information. Use the power of the PalWeb Dictionary to build your own
                    custom Decks in a flash. Need some inspiration? Browse the Deck Library to see all the Decks others
                    have made — & even copy a Deck to put your own spin on someone else's idea!
                </div>
            </div>

            <div class="feature-panel-feature">
                <video autoplay muted loop>
                    <source src="https://abdulbaha.fra1.digitaloceanspaces.com/videos/db-demo.mov">
                </video>
            </div>
        </div>

        <div class="feature-panel inline reverse">
            <div class="feature-panel-content">
                <div class="feature-panel-title">card viewer</div>
                <div class="feature-panel-subtitle">Flashy new ways to study.</div>
                <div class="feature-panel-description">Tired of micro-managing third-party flashcard applications?
                    Study your Deck right here with the interactive Card Viewer! The Card Viewer gives you total
                    flexibility to adjust how you view your Deck with just one click. (Quizzes &
                    other modes will be coming soon!)
                </div>
            </div>
            <div class="feature-panel-feature">
                <video autoplay muted loop>
                    <source src="https://abdulbaha.fra1.digitaloceanspaces.com/videos/cv-demo.mov">
                </video>
            </div>
        </div>

        <div class="feature-panel inline">
            <div class="feature-panel-content">
                <div class="feature-panel-title">record wizard</div>
                <div class="feature-panel-subtitle">Let your voice shine through.</div>
                <div class="feature-panel-description">Originally developed to easily source pronunciation samples from
                    native speakers, the Record Wizard will be open for all to contribute audios to the site. Fluency
                    level is indicated for each Speaker, so don't be shy — every word can have as many audios as there
                    are Arabic speakers in the world!
                </div>
            </div>

            <div class="feature-panel-feature">
                <video autoplay muted loop>
                    <source src="https://abdulbaha.fra1.digitaloceanspaces.com/videos/rw-demo.mov">
                </video>
            </div>
        </div>
    </div>
    <div class="feature-panel-wrapper yellow">
        <div>
            <img src="{{ asset('img/globe-america.svg') }}" class="world" alt="America"/>
            <img src="{{ asset('img/globe-africa.svg') }}" class="world" alt="Africa"/>
            <img src="{{ asset('img/globe-asia.svg') }}" class="world" alt="Asia"/>
        </div>

        <div class="feature-panel" style="max-width: 96rem">
            <div class="feature-panel-content">
                <div class="feature-panel-title">building community</div>
                <div class="feature-panel-subtitle">Connect & share with others.</div>
            </div>
            <div class="feature-panel-feature">
                @include('users._profile', ['user' => \App\Models\User::find(1)])
            </div>
        </div>

        <div class="carousel-wrapper">
            <div class="carousel-track">
                @foreach(\App\Models\User::find([7, 10, 11, 18, 19, 878, 1113, 1115, 1186, 1224])->all() as $user)
                    <x-user-portrait :user="$user" size="l"/>
                @endforeach
            </div>
        </div>

        <div class="model-counter-wrapper">
            <div class="model-counter">
                <div class="model-counter-count">{{ \App\Models\Deck::count() }}</div>
                <div class="model-counter-body">
                    <span class="model-counter-model">Decks</span>
                </div>
            </div>
            <div class="model-counter">
                <div class="model-counter-count">{{ \App\Models\Audio::count() }}</div>
                <div class="model-counter-body">
                    <span class="model-counter-model">Audios</span>
                </div>
            </div>
            <div class="model-counter">
                <div class="model-counter-count">{{ \App\Models\User::count() }}</div>
                <div class="model-counter-body">
                    <span class="model-counter-model">Pals</span>
                </div>
            </div>
        </div>

        {{--        <div class="feature-panel" style="max-width: 96rem">--}}
        {{--            <div class="feature-panel-content">--}}
        {{--                <div class="feature-panel-title">deck library</div>--}}
        {{--                <div class="feature-panel-subtitle">Build your vocabulary. Build Decks.</div>--}}
        {{--                <div class="feature-panel-description">Don't want to make your own Decks? Browse the Deck Library to see--}}
        {{--                    all the Decks others have made — & even copy a Deck to put your own spin on someone else's idea!--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </div>--}}

        <div class="carousel-wrapper">
            <div class="carousel-track" style="animation-direction: reverse">
                @foreach(\App\Models\Deck::find([2, 3, 4, 12, 19, 83, 100, 118])->all() as $deck)
                    <x-vue.deck component="DeckFlashcard" :deck="$deck"/>
                @endforeach
            </div>
        </div>

        {{--        <div class="feature-panel inline">--}}
        {{--            <div class="feature-panel-content">--}}
        {{--                <div class="feature-panel-title">audio library</div>--}}
        {{--                <div class="feature-panel-subtitle">Hear it from the horse’s mouth.</div>--}}
        {{--                <div class="feature-panel-description">Lorem ipsum--}}
        {{--                </div>--}}
        {{--            </div>--}}

        {{--            <div class="feature-panel-feature">--}}
        {{--                <img src="{{ asset('img/audio-library.png') }}" alt="Audio Library"/>--}}
        {{--            </div>--}}
        {{--        </div>--}}

        <div class="feature-panel-content">
            <div class="feature-panel-subtitle">& so much more ...</div>
        </div>
    </div>

    <div class="feature-panel-wrapper">
        <div class="portal-button-wrapper">
            <div class="portal-button-head">
                what will you create?
            </div>
            <div class="portal-button-body">
                <a href="{{ route('signup') }}" class="portal-button">Get Started!</a>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
    <script>
        var typed = new Typed('#typed-element', {
            strings: ['PalWeb gives you the tools to do so.'],
            typeSpeed: 50,
        });

        const carousels = document.querySelectorAll('.carousel-wrapper');
        if (!window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
            addAnimation();
        }

        function addAnimation() {
            carousels.forEach((carousel) => {
                carousel.setAttribute("data-animated", true)

                const carouselTrack = carousel.querySelector('.carousel-track');
                const carouselContent = Array.from(carouselTrack.children);

                carouselContent.forEach(item => {
                    const clone = item.cloneNode(true);
                    clone.setAttribute('aria-hidden', true);
                    carouselTrack.appendChild(clone);
                });
            });
        }
    </script>
@endsection
