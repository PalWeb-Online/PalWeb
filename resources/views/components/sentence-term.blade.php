@props([
  'arb',
  'eng',
  'term' => false,
  'isCurrent' => false
])

@if($term)
    <a href="{{ $isCurrent ? '#' : route('terms.show', $term) }}"
       target="{{ $isCurrent ? '' : '_blank' }}" class="sentence-term {{ $isCurrent ? 'active' : '' }}">
        <div>{{ $arb ?? $term->term }}</div>
        <div>{{ $eng ?? $term->translit }}</div>
    </a>
@elseif(isset($arb))
    <div class="sentence-term">
        <div>{{ $arb }}</div>
        <div>{{ $eng }}</div>
    </div>
@else
@endif
