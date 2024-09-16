@props([
    'term' => false,
    'gloss' => false,
    'arb' => false,
    'eng' => false,
    'subterm' => false,
])

@if($term)
    <div class="term-li-container">
        <x-vue.term component="TermItem" :term="$term" :gloss="$gloss" />
        {{ $slot }}
    </div>

@elseif($subterm)
    <div class="term-li subterm">
        <div class="arb">{{ $arb }}</div>
        <div class="eng">{{ $eng }}</div>
    </div>

@elseif($arb)
    <div class="term-li-container">
        <div class="term-li-wrapper">
            <div class="term-li">
                <div class="arb">{{ $arb }}</div>
                <div class="eng">{{ $eng }}</div>
            </div>
        </div>
        {{ $slot }}
    </div>
@else
    <div class="term-li coming-soon">
        <div class="feature-callout">
            {{ __('coming soon') }}
        </div>
    </div>
@endif
