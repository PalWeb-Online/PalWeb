@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel" class="with-feature">
        <h1>{{ __('dictionary') }}</h1>
    </div>
@endsection

@section('content')
    <div class="splash-panel" style="width: 100%;">
        <div class="sp-head">
            <div>{{ __('terms.edit') }}</div>
        </div>
        <div id="termEditor">
            <term-editor mode="edit" term-id="{{ $term->id }}"></term-editor>
        </div>
    </div>
@endsection
