<div class="info-button-wrapper">
    <div class="info-button-label">{{ $label }}</div>
    @if($arb !== '')
        <div class="info-button-content">
            <div class="info-button-content-arb">
                {{ $arb }}
            </div>
            @isset($eng)
                <div class="info-button-content-eng">
                    {{ $eng }}
                </div>
            @endisset
        </div>
    @endif
</div>
