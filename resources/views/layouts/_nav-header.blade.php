<nav class="nav-header">
    <a class="logo" href="{{ route('homepage') }}">
        <img src="{{ asset('/img/logo.svg') }}" alt="PalWeb Logo"/>
    </a>

    <div class="nav-header-body">
        @include('layouts._nav-menu')

        <form method="GET" action="{{ route('terms.index') }}">
            <div data-vue-component="SearchBar"></div>
        </form>
    </div>
</nav>

{{--        <x-dropdown>--}}
{{--            <x-slot name="trigger">--}}
{{--                <a class="material-symbols-rounded" @click="open = ! open">language</a>--}}
{{--            </x-slot>--}}
{{--            <x-slot name="content">--}}
{{--                <form id="frm-en" method="post" action="{{ route("language.change", "en") }}">--}}
{{--                    @csrf--}}
{{--                    <button onclick="this.closest('form').submit();return false;">EN</button>--}}
{{--                </form>--}}

{{--                <form id="frm-es" method="post" action="{{ route("language.change", "es") }}">--}}
{{--                    @csrf--}}
{{--                    <button onclick="this.closest('form').submit();return false;">ES</button>--}}
{{--                </form>--}}

{{--                <form id="frm-ar" method="post" action="{{ route("language.change", "ar") }}">--}}
{{--                    @csrf--}}
{{--                    <button onclick="this.closest('form').submit();return false;">AR</button>--}}
{{--                </form>--}}
{{--            </x-slot>--}}
{{--        </x-dropdown>--}}
