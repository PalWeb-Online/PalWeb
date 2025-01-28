<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}" @if (app()->getLocale() == 'ar') dir="rtl" @else dir="ltr" @endif>
<head>
    <title>{{ isset($pageTitle) ? $pageTitle . ' | ' : '' }}{{ config('app.name') }}</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description"
          content="{{ $pageDescription ?? 'PalWeb: the Web of Palestinian Arabic | Get your study on with PalWeb\'s database-powered Palestinian Arabic learning tools: search the Dictionary & the Phrasebook; create & share your own custom flashcard Decks; connect with a vibrant language-learning community.' }}">
    <meta name="author" content="R. Adrian">

    <meta property="og:title" content="{{ isset($pageTitle) ? $pageTitle . ' | ' : '' }}{{ config('app.name') }}">
    <meta property="og:description"
          content="{{ $pageDescription ?? 'PalWeb: the Web of Palestinian Arabic | Get your study on with PalWeb\'s database-powered Palestinian Arabic learning tools: search the Dictionary & the Phrasebook; create & share your own custom flashcard Decks; connect with a vibrant language-learning community.' }}">
    <meta property="og:image" content="{{ url('/img/palweb.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@palweb_app">
    <meta name="twitter:creator" content="@rafi2_ab">
    <meta name="twitter:title" content="{{ isset($pageTitle) ? $pageTitle . ' | ' : '' }}{{ config('app.name') }}">
    <meta name="twitter:description"
          content="{{ $pageDescription ?? 'PalWeb: the Web of Palestinian Arabic | Get your study on with PalWeb\'s database-powered Palestinian Arabic learning tools: search the Dictionary & the Phrasebook; create & share your own custom flashcard Decks; connect with a vibrant language-learning community.' }}">
    <meta name="twitter:image" content="{{ url('/img/palweb.png') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://use.typekit.net/wob8zmj.css">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,400;0,700;1,400&display=swap"
          rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;700;900&display=swap"
          rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;700&display=swap"
          rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0"
          rel="stylesheet">

    <link rel="icon" href="{{ asset('img/map.svg') }}">

    <script src="https://code.jquery.com/jquery-3.6.1.js"
            integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.3/howler.min.js"></script>

    <script>
        window.Laravel = @json([
            'user' => auth()->user()->load('roles'),
        ]);
    </script>

    @routes
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])

</head>

<body class="{{ $layout ?? '' }} {{ $bodyBackground ?? '' }}">

@include("layouts._nav-mobile")
@include("layouts._nav-sticky")
@include("layouts._nav-user")
@include('layouts._nav-header')

@yield('page-hero')
@yield('page-body')

@include("layouts._footer")

@if(!request()->routeIs('sentences.create', 'sentences.edit', 'dialogs.create', 'dialogs.edit', 'decks.create', 'decks.edit', 'decks.study', 'audios.record'))
    <div id="searchGenie"></div>
@endif

</body>

<script type="text/javascript" src="{{ asset('js/main.js') }}?v={{ filemtime(public_path('js/main.js')) }}"></script>

</html>
