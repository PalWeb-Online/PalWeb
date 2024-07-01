@extends ('layouts.main')

@section('content')

    <x-lesson unit="1" lesson="1">

        <x-collapsible title="{{ __('know-this') }} | لازم تكون عارف">
            <h1>roots & patterns</h1>
            <p>Like other Semitic languages, Arabic primarily creates words via a system of <b>roots & patterns</b>.
                Nearly all native Arabic words may be distilled into a three-letter — or occasionally four-letter —
                root, placed into one of many word patterns that in turn have their own meanings. While a term's root
                may convey a general theme (e.g. cooking), the term's pattern may contextualize that
                theme, indicating that it's a place for cooking (i.e. a kitchen), or the person that cooks (i.e. a
                chef).</p>
            <div class="array">
                <x-sentence eng="to cook">
                    <x-sentence-term arb="طبخ" eng="ṭabax" :term="$terms['ṭabax'] ?? null"/>
                </x-sentence>
                <x-sentence eng="kitchen">
                    <x-sentence-term arb="مطبخ" eng="maṭbax" :term="$terms['maṭbax'] ?? null"/>
                </x-sentence>
                <x-sentence eng="chef">
                    <x-sentence-term arb="طبّاخ" eng="ṭabbāx" :term="$terms['ṭabbāx'] ?? null"/>
                </x-sentence>
            </div>
            <p>While English learners have to memorize the meanings of suffixes like <b>"-ing"</b> or <b>"-ly"</b>,
                Arabic learners should get familiar with these word patterns, since they fulfill a very similar role. In
                fact, many patterns are associated with a specific word category or part of speech, like verbs or
                adjectives.</p>
            <div class="array">
                <x-sentence eng="to wash">
                    <x-sentence-term arb="غسل" eng="ġasal" :term="$terms['ġasal'] ?? null"/>
                </x-sentence>
                <x-sentence eng="sink">
                    <x-sentence-term arb="مغسلة" eng="maġsale" :term="$terms['maġsale'] ?? null"/>
                </x-sentence>
                <x-sentence eng="washing machine">
                    <x-sentence-term arb="غسّالة" eng="ġassāle" :term="$terms['ġassāle'] ?? null"/>
                </x-sentence>
            </div>
            <p>Because of that, you can
                sometimes infer the meanings of new terms by combining the meanings of their roots &
                patterns. Besides, since word patterns always have the same vowels, if you recognize a word's pattern in
                writing, you'll never have to guess its voweling. Don't learn Arabic the hard way!</p>

            <x-vocabulary title="patterns">
                <x-term arb="ma12a3" eng="(Location Pattern)"/>
                <x-term arb="1a22ā3" eng="(Agent Pattern)"/>
            </x-vocabulary>
        </x-collapsible>

        <x-nav-modules
            img="https://upload.wikimedia.org/wikipedia/commons/b/b2/Earth_from_space_%287628218100%29.jpg"
            position="0%"/>

        <div x-data="{ tab: 'm1' }" @tab-changed.window="tab = $event.detail" class="tabs">
            @foreach (range(1, 3) as $m)
                <div x-show="tab === 'm{{ $m }}'">
                    @include('lessons.u1.l1.m' . $m, ['u' => 1, 'l' => 1, 'm' => $m, 'terms' => $terms, 'sentences' => $sentences])
                </div>
            @endforeach
        </div>
    </x-lesson>

@endsection
