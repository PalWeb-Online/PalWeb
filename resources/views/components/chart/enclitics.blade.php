@php
    $longVowels = ['ā', 'ē', 'ī', 'ō', 'ū'];

    if ($form === 'genitive') {
        if (in_array(mb_substr($translit, -1), ['ā', 'ō', 'ū'])) {
            $ar1S = $host . 'ي';
            $tr1S = $translit . 'y';

        } elseif (mb_substr($translit, -1) === 'ē') {
            $ar1S = $host . 'ّ';
            $tr1S = mb_substr($translit, 0, -1) . 'ayy';

        } elseif (mb_substr($translit, -1) === 'ī') {
            $ar1S = $host . 'ّي';
            $tr1S = mb_substr($translit, 0, -1) . 'iyyi';

        } else {
            $ar1S = $host . 'ي';
            $tr1S = $translit . 'i';
        }
    } elseif ($form === 'accusative') {
        $ar1S = $host . 'ني';
        $tr1S = $translit . 'ni';
    }

@endphp

<div class="inflection-chart-wrapper">
    <div class="inflection-chart">
        <div class="inflection-chart-item">
            <div>1S</div>
            <div>
                <div>{{ $ar1S }}</div>
                <div>{{ $tr1S }}</div>
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
                <div>{{ $translit }}{{ !in_array(mb_substr($translit, -1), $longVowels) ? 'ik' : 'ki' }}</div>
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
                <div>{{ $translit }}{{ !in_array(mb_substr($translit, -1), $longVowels) ? 'o' : '(h)' }}</div>
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
