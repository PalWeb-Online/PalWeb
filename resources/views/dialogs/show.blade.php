@extends ('layouts.main')

@section('content')
    <x-page-head>
        <x-link :href="route('dialogs.index')">{{ __('dialogs') }}</x-link>
        <x-link :href="route('dialogs.show', $dialog)">{{ __('view') }}</x-link>
    </x-page-head>

    <x-vue.dialog component="DialogContainer" :dialog="$dialog"/>
@endsection
