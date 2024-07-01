<div class="page-head">
    <div class="breadcrumbs">
        {{ $slot }}
    </div>

    @isset($title)
        <div class="page-title">{{ __($title) }}</div>
    @endisset

    @isset($blurb)
        <div class="page-blurb">{{ __($blurb) }}</div>
    @endisset
</div>
