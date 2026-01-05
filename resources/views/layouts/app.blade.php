<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description"
          content="{{ $pageDescription ?? 'PalWeb: the Web of Palestinian Arabic | Get your study on with PalWeb\'s database-powered Palestinian Arabic learning tools: search the Dictionary & the Corpus; create & share your own custom flashcard Decks; connect with a vibrant language-learning community.' }}">
    <meta name="author" content="R. Adrian">

    <meta property="og:title" content="{{ isset($pageTitle) ? $pageTitle . ' | ' : '' }}{{ config('app.name') }}">
    <meta property="og:description"
          content="{{ $pageDescription ?? 'PalWeb: the Web of Palestinian Arabic | Get your study on with PalWeb\'s database-powered Palestinian Arabic learning tools: search the Dictionary & the Corpus; create & share your own custom flashcard Decks; connect with a vibrant language-learning community.' }}">
    <meta property="og:image" content="{{ url('/img/palweb.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@palweb_app">
    <meta name="twitter:creator" content="@rafi2_ab">
    <meta name="twitter:title" content="{{ isset($pageTitle) ? $pageTitle . ' | ' : '' }}{{ config('app.name') }}">
    <meta name="twitter:description"
          content="{{ $pageDescription ?? 'PalWeb: the Web of Palestinian Arabic | Get your study on with PalWeb\'s database-powered Palestinian Arabic learning tools: search the Dictionary & the Corpus; create & share your own custom flashcard Decks; connect with a vibrant language-learning community.' }}">
    <meta name="twitter:image" content="{{ url('/img/palweb.png') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://use.typekit.net" crossorigin>

    <link rel="stylesheet" href="https://use.typekit.net/wob8zmj.css">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;700&family=JetBrains+Mono:ital,wght@0,400;0,700;1,400&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&family=Vazirmatn:wght@400;700;900&family=Lalezar&display=swap" rel="stylesheet">

    <link rel="icon" href="{{ asset('img/map.svg') }}">

    @routes
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    @inertiaHead
</head>

<body class="{{ $bodyBackground ?? '' }}">
    @inertia
</body>
</html>
