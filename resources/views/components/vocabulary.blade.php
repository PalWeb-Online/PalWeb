<div class="terms-list">
    @isset($title)
        <h1>{{ __($title) }}</h1>
    @endisset
    {{ $slot }}
</div>
