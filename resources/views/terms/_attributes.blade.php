@foreach($attributes->pluck('attribute') as $attribute)
    @php
        $attribute = $attribute === 'masculine' ? 'm' : $attribute;
        $attribute = $attribute === 'feminine' ? 'f' : $attribute;
        $attribute = $attribute === 'plural' ? 'p' : $attribute;
    @endphp

    <div class="chart-item {{ $attribute }}"
         @if(in_array($attribute, ['m', 'f', 'p']))
             style="border-radius:50%"
        @endif
    >
        <div class="chart-title"
             @if(in_array($attribute, ['m', 'f', 'p']))
                 style="text-transform:uppercase; border-radius:50%; width:2.8rem; text-align:center"
            @endif
        >
            {{ $attribute }}
        </div>
    </div>
@endforeach

{{--    @elseif(in_array($term->category->subtype, ['defect', 'quantifier', 'complementizer']))--}}
{{--        <div class="chart-item blu">--}}
{{--            <div class="chart-title">--}}
{{--                {{ __($term->category->subtype) }}--}}
{{--            </div>--}}
{{--        </div>--}}

{{--    @switch($attribute)--}}
{{--        @case('pseudo')--}}
{{--            <a href="/docs/verbs#pseudo" target="_blank"--}}
{{--               class="chart-item mag">--}}
{{--                <div class="chart-title">--}}
{{--                    pseudo--}}
{{--                </div>--}}
{{--            </a>--}}
{{--            @break--}}
{{--        @case('participle')--}}
{{--            <a href="/docs/adjectives#participle" target="_blank"--}}
{{--               class="chart-item yel">--}}
{{--                <div class="chart-title">--}}
{{--                    participle--}}
{{--                </div>--}}
{{--            </a>--}}
{{--            @break--}}
{{--    @endswitch--}}

{{--@if($term->attributes->contains('attribute', 'clitic'))--}}
{{--    <a href="/docs/parts-of-speech#clitic"--}}
{{--       target="_blank" class="chart-item orn">--}}
{{--        <div class="chart-title">--}}
{{--            clitic--}}
{{--        </div>--}}
{{--    </a>--}}
{{--@elseif($term->attributes->contains('attribute', 'interrogative'))--}}
{{--    <div class="chart-item orn">--}}
{{--        <div class="chart-title">--}}
{{--            interrogative--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endif--}}
