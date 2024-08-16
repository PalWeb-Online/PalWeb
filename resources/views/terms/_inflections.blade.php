@if(count($term->inflections->where('form', 'host')) > 0)
    @if($term->slug === 'preposition-b-')
        <x-conj.b/>
    @else
        @foreach ($term->inflections->where('form', 'host') as $hostForm)
            <x-conj.inflection host="{{ $hostForm->inflection }}" translit="{{ $hostForm->translit }}"/>
        @endforeach
    @endif

@elseif($term->category == 'verb')
    @foreach($term->patterns as $pattern)
        @if ($pattern->form == '1')

{{--            TODO: rootArray is being called every single time individually --}}
            <x-conj.1
                r1='{{ $term->root->rootArray()[0][0] }}'
                r2='{{ $term->root->rootArray()[0][1] }}'
                r3='{{ $term->root->rootArray()[0][2] }}'
                r1tr='{{ $term->root->rootArray()[1][0] }}'
                r2tr='{{ $term->root->rootArray()[1][1] }}'
                r3tr='{{ $term->root->rootArray()[1][2] }}'
                form='{{ $pattern->pattern }}'
            ></x-conj.1>

        @elseif (in_array($pattern->form, ['2', '3', '5', '6']))
            <x-conj.2536
                r1='{{ $term->root->rootArray()[0][0] }}'
                r2='{{ $term->root->rootArray()[0][1] }}'
                r3='{{ $term->root->rootArray()[0][2] }}'
                r1tr='{{ $term->root->rootArray()[1][0] }}'
                r2tr='{{ $term->root->rootArray()[1][1] }}'
                r3tr='{{ $term->root->rootArray()[1][2] }}'
                form='{{ $pattern->form }}{{ $pattern->pattern }}'
            ></x-conj.2536>

        @elseif (in_array($pattern->form, ['4', '7', '8']))
            <x-conj.478
                r1='{{ $term->root->rootArray()[0][0] }}'
                r2='{{ $term->root->rootArray()[0][1] }}'
                r3='{{ $term->root->rootArray()[0][2] }}'
                r1tr='{{ $term->root->rootArray()[1][0] }}'
                r2tr='{{ $term->root->rootArray()[1][1] }}'
                r3tr='{{ $term->root->rootArray()[1][2] }}'
                form='{{ $pattern->form }}{{ $pattern->pattern }}'
            ></x-conj.478>

        @elseif (in_array($pattern->form, ['9', 'X']))
            <x-conj.9X
                r1='{{ $term->root->rootArray()[0][0] }}'
                r2='{{ $term->root->rootArray()[0][1] }}'
                r3='{{ $term->root->rootArray()[0][2] }}'
                r1tr='{{ $term->root->rootArray()[1][0] }}'
                r2tr='{{ $term->root->rootArray()[1][1] }}'
                r3tr='{{ $term->root->rootArray()[1][2] }}'
                form='{{ $pattern->form }}{{ $pattern->pattern }}'
            ></x-conj.9X>

        @elseif (in_array($pattern->form, ['2Q', '5Q']))
            <x-conj.Q
                r1='{{ $term->root->rootArray()[0][0] }}'
                r2='{{ $term->root->rootArray()[0][1] }}'
                r3='{{ $term->root->rootArray()[0][2] }}'
                r4='{{ $term->root->rootArray()[0][3] ?? null }}'
                r1tr='{{ $term->root->rootArray()[1][0] }}'
                r2tr='{{ $term->root->rootArray()[1][1] }}'
                r3tr='{{ $term->root->rootArray()[1][2] }}'
                r4tr='{{ $term->root->rootArray()[1][3] ?? null }}'
                form='{{ $pattern->form }}{{ $pattern->pattern }}'
            ></x-conj.Q>
        @endif
    @endforeach
@endif
