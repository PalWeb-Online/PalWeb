@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel">
        <h1>{{ __('dialogs') }}</h1>
    </div>
@endsection

@section('content')

    @if($dialogs->count() > 0)
        <div class="dialogs-list">
            @foreach($dialogs as $dialog)
                <x-vue.dialog :dialog="$dialog" component="DialogItem"/>
            @endforeach
        </div>
    @endif

    {{ $dialogs->links() }}

@endsection
