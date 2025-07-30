@extends ('layouts.app')

@section('main-body')
    @include("layouts._nav-user")

    @yield('page-hero')

    <div id="page-body">
        @if(request()->routeIs('wiki.index', 'wiki.show'))
        @endif
        <div id="page-content">
            @yield('content')
        </div>
    </div>
@endsection
