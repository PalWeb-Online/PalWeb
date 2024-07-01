<div class="dialog-line-wrapper">
    <div class="dialog-line-container">
        @isset($speaker)
            <div class="dialog-line-head">
                {{ $speaker }}
            </div>
        @endisset
        <div class="dialog-line-body">
            <div class="dialog-arb">
                {{ $arb }}
            </div>
            @isset($eng)
                <div class="dialog-eng">
                    {{ $eng }}
                </div>
            @endisset
        </div>
    </div>
</div>
