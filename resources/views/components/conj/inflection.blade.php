@php
    $longVowels = ['ā', 'ē', 'ī', 'ō', 'ū'];
@endphp

<div class="inflection-chart-wrapper">
    <div class="inflection-chart">
        <div class="inflection-chart-item">
            <div>1S</div>
            <div>
                <div>{{ $host }}{{ mb_substr($translit, -1) == 'ē' ? 'ّ' : 'ي' }}</div>
                <div>{{ mb_substr($translit, -1) == 'ē' ? mb_substr($translit, 0, -1).'ay' : $translit }}{{ !in_array(mb_substr($translit, -1), $longVowels) ? 'i' : 'y' }}</div>
            </div>
        </div>
        <div class="inflection-chart-item">
            <div>1P</div>
            <div>
                <div>{{ $host }}نا</div>
                <div>{{ $translit }}na</div>
            </div>
        </div>
        <div class="inflection-chart-item">
            <div>2M</div>
            <div>
                <div>{{ $host }}ك</div>
                <div>{{ $translit }}{{ !in_array(mb_substr($translit, -1), $longVowels) ? 'ak' : 'k' }}</div>
            </div>
        </div>
        <div class="inflection-chart-item">
            <div>2F</div>
            <div>
                <div>{{ $host }}{{ !in_array(mb_substr($translit, -1), $longVowels) ? 'ك' : 'كي' }}</div>
                <div>{{ $translit }}{{ !in_array(mb_substr($translit, -1), $longVowels) ? 'ek' : 'ki' }}</div>
            </div>
        </div>
        <div class="inflection-chart-item" style="grid-column: span 2">
            <div>2P</div>
            <div>
                <div>{{ $host }}كم</div>
                <div>{{ $translit }}kom</div>
            </div>
        </div>
        <div class="inflection-chart-item">
            <div>3M</div>
            <div>
                <div>{{ $host }}ه</div>
                <div>{{ $translit }}{!! !in_array(mb_substr($translit, -1), $longVowels) ? 'o' : '<sup>h</sup>' !!}</div>
            </div>
        </div>
        <div class="inflection-chart-item">
            <div>3F</div>
            <div>
                <div>{{ $host }}ها</div>
                <div>{{ $translit }}ha</div>
            </div>
        </div>
        <div class="inflection-chart-item" style="grid-column: span 2">
            <div>3P</div>
            <div>
                <div>{{ $host }}هم</div>
                <div>{{ $translit }}hom</div>
            </div>
        </div>
    </div>
</div>
