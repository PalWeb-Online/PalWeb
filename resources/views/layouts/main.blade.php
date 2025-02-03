@extends ('layouts.app')

@section('main-body')
    @include("layouts._nav-mobile")
    @include("layouts._nav-sticky")
    @include("layouts._nav-user")
    @include('layouts._nav-header')

    @yield('page-hero')

    <div id="page-body">
        @if(request()->routeIs('wiki.index', 'wiki.show'))
            @include("layouts._nav-wiki")
        @endif
        <div id="page-content">
            @yield('content')
        </div>
    </div>

    <div id="searchGenie"></div>

    @include("layouts._footer")
@endsection
