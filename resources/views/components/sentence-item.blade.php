@props([
    'sentence' => false,
    'size' => 'm',
    'eng' => false,
])

@if($sentence)
    <x-vue.sentence component="SentenceItem" :size="$size" :sentence="$sentence" />

@elseif($eng)
    <div class="sentence-wrapper" style="justify-self: center">
        <div class="sentence {{ $size }}">
            <div class="sentence-arb">
                {{ $slot }}
            </div>
            <div class="sentence-eng">
                {{ $eng }}
            </div>
        </div>
    </div>

@else
    <div class="sentence coming-soon">
        <div class="feature-callout">
            {{ __('coming soon') }}
        </div>
    </div>
@endif
