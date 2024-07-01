<div class="form">
    @isset($title)
        <h1>{{ $title }}</h1>
    @endisset

    {{ $slot }}
</div>
