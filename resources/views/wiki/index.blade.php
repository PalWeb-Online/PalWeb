@extends ('layouts.main')

@section('content')

    <x-page-head title="{{ __('wiki') }}"
                 blurb="Everything you need to know about Palestinian Arabic & PalWeb, in one place.">
        <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>
        <x-link :href="route('wiki.index')">{{ __('home') }}</x-link>
    </x-page-head>

    <div class="doc-section">
        <p>
            Welcome to the <b>PalWeb Wiki</b>! Use the menu on the left (tap the
            green arrow to view it on mobile) to navigate between sections.</p>
        <p>If you want to know what the project is all about, visit the <a
                href="{{ route('wiki.show', 'about') }}">About</a> page.</p>
        <p>If you have some specific questions or troubleshooting problems, visit the <a
                href="{{ route('wiki.show', 'faq') }}">FAQ</a>.
        </p>
        <p>If you want to know about the features of the site & how to use it, see the pages under <b>the Website</b>.
        </p>
        <p>If you want a general overview of what Palestinian Arabic is, see the pages under <b>the Language</b>.</p>
        <p>If you want a detailed, technical description of Palestinian Arabic, see everything else!</p>
    </div>

@endsection
