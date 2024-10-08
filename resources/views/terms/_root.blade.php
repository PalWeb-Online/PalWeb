@php
    $rootTerms = $term->root->terms->sortBy('term');
    $root = $term->root->generateRoot();
@endphp

<div class="term-root" x-data="{ open: false }">
    <div class="term-root-head">
        <div class="term-root-head-arb">{{ $root[0] }}</div>
        <div class="term-root-head-eng">({{ $root[1] }})</div>
    </div>
    @unless(count($rootTerms) == 1)
        <div class="term-root-body" x-show="open">
            @foreach($rootTerms as $rootTerm)
                @unless($rootTerm->id == $term->id)
                    <a href="{{ route('terms.show', $rootTerm) }}">{{ $rootTerm->term }}
                        ({{ $rootTerm->translit }})
                    </a>
                @endunless
            @endforeach
        </div>
        <button @click="open = !open" x-text="open ? 'expand_less' : 'expand_more'"
                class="material-symbols-rounded">
        </button>
    @endunless
</div>
