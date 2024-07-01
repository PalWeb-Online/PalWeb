@extends ('layouts.main')

@section('content')

    <x-page-head title="{{ __('community') }}">
        <x-slot name="crumbs">
            <x-link :href="route('community.index')">{{ __('community') }}</x-link>
            <x-link :href="route('community.index')">{{ __('home') }}</x-link>
        </x-slot>
    </x-page-head>

@endsection
