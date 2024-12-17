<form class="search-tools" method="GET" action="{{ route($route) }}">
    <div class="search-bar">
        <input type="text" name="search" placeholder="دوّر" value="{{ request('search') }}">
        <button class="search" type="submit">
            <img src="{{ asset('/img/search.svg') }}" alt="Search"/>
        </button>
    </div>
</form>
