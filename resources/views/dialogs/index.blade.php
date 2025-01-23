@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel" class="with-feature">
        <h1>{{ __('dialogs') }}</h1>
    </div>
@endsection

@section('content')

    <div class="portal-grid">
        @foreach($dialogs as $dialog)
            <x-portal-item subtitle="dialog" :maintitle="$dialog->title" :link="route('dialogs.show', $dialog)"
                           :blurb="$dialog->description ?? null"
                           img="https://upload.wikimedia.org/wikipedia/commons/f/f6/Coronavirus_SARS-CoV-2.jpg"
            />
        @endforeach
    </div>

    {{ $dialogs->links() }}

@endsection
