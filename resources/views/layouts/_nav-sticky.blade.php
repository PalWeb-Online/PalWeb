<nav class="nav-sticky">
    <a class="logo" href="{{ route('homepage') }}">
        <img src="{{ asset('/img/logo.svg') }}" alt="PalWeb Logo"/>
    </a>

    @include('layouts._nav-menu')

    <form method="GET" action="{{ route('terms.index') }}">
        <div data-vue-component="SearchBar"></div>
    </form>
</nav>
