@extends ('layouts.main')

@section('content')

    <x-page-head :title="$dialog->title"
                 :blurb="$dialog->description ?? null">
        <x-link :href="route('dialogs.index')">{{ __('dialogs') }}</x-link>
        <x-link :href="route('dialogs.show', $dialog)">{{ __('view') }}</x-link>
    </x-page-head>

    @if($dialog->media)
        <iframe src="{{ $dialog->media }}" allowfullscreen></iframe>
    @endif

    <div class="activity-dialog">
        @foreach ($dialog->sentences as $sentence)
            <x-dialog-line :speaker="$sentence->speaker" :arb="$sentence->sentence"
                           :eng="$sentence->trans"/>
        @endforeach
    </div>
@endsection
