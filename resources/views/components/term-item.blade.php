@props([
    'term' => false,
    'gloss' => false,
    'arb' => false,
    'eng' => false,
    'subterm' => false,
])

@if($term)
    <div class="term-item-container">
        <x-vue.term component="TermItem" :term="$term" :gloss="$gloss" />
        {{ $slot }}
    </div>

@elseif($subterm)
    <div class="term-item subterm">
        <div class="arb">{{ $arb }}</div>
        <div class="eng">{{ $eng }}</div>
    </div>

@elseif($arb)
    <div class="term-item-container">
        <div class="term-item-wrapper">
            <div class="term-item">
                <div class="arb">{{ $arb }}</div>
                <div class="eng">{{ $eng }}</div>
            </div>
        </div>
        {{ $slot }}
    </div>
@else
    <div class="term-item coming-soon">
        <div class="feature-callout">
            {{ __('coming soon') }}
        </div>
    </div>
@endif
