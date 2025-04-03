@extends ('layouts.main')

@section('page-hero')
    {{--    @include("layouts._lesson-nav", [--}}
    {{--        'unit' => null,--}}
    {{--        'lesson' => null--}}
    {{--        ]--}}
    {{--    )--}}

    <div id="hero-panel">
        <h1>{{ __('academy') }}</h1>
    </div>
@endsection

@section('content')
    <div class="featured-title l">{{ __('level') }} 1</div>
    <div class="portal-grid">
        <x-portal-item subtitle="{{ __('unit') }} 1" maintitle="{{ __('lessons.1') }}"
                       link="{{ route('academy.unit', '1') }}"
                       blurb="Introduce yourself & describe the world around your using simple nominal sentences."
                       img="https://upload.wikimedia.org/wikipedia/commons/9/95/Earth_from_Space-_Eastern_Mediterranean_ESA24921278.jpg"
        />
        <x-portal-item subtitle="{{ __('unit') }} 2" maintitle="{{ __('lessons.2') }}"
                       link="{{ route('academy.unit', '2') }}"
                       blurb="Talk about your daily habits & loved ones using the Genitive Structure & your first Pseudo-Verbs."
                       img="https://upload.wikimedia.org/wikipedia/commons/a/a9/Palestinian_Kids_in_Nazareth_by_David_Shankbone.jpg"
        />
        <x-portal-item subtitle="{{ __('unit') }} 3" maintitle="{{ __('lessons.3') }}"
                       link="{{ route('academy.unit', '3') }}"
                       blurb="Learn the remaining Pseudo-Verbs & finally master the Arabic nominal sentence."
                       img="https://upload.wikimedia.org/wikipedia/commons/thumb/1/13/Ali_Gholi_Agha_hammam%2C_Isfahan%2C_Iran.jpg/1154px-Ali_Gholi_Agha_hammam%2C_Isfahan%2C_Iran.jpg"
        />
    </div>

    <div class="portal-button-wrapper">
        <div class="portal-button-head">
            ready for the next step?
        </div>
        <div class="portal-button-body">
            <a href="{{ route('dialogs.index') }}" class="portal-button">Texts Portal</a>
        </div>
    </div>
@endsection
