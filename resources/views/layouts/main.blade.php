@extends ('layouts.app')

@section('page-body')
    <div id="page-body">

        @if(request()->routeIs('wiki.index', 'wiki.show'))
            @include("layouts._nav-wiki")
        @endif

        <div id="page-content">
            @yield('content')
        </div>
    </div>
@endsection
