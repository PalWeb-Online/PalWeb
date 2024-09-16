@extends ('layouts.main')

@section('content')

    <x-lesson unit="1" lesson="2">

        <x-collapsible title="{{ __('know-this') }} | لازم تكون عارف">
            <h1>broken plurals</h1>

            <p>Pluralizing nouns is not particularly straightforward in Arabic. Most nouns have plural forms created by
                modifying the word's pattern, rather than by attaching suffixes like <b>"-s"</b> in English. Since they
                mix up
                the original pattern, these are known as
                <b>broken plurals</b>.</p>

            <div class="array">
                <x-sentence-item eng="these are children">
                    <x-sentence-term arb="هذول" eng="these" :term="$terms['hadōl'] ?? null"/>
                    <x-sentence-term arb="ولاد" eng="children" :term="$terms['walad'] ?? null"/>
                </x-sentence-item>
                <x-sentence-item eng="this is a child">
                    <x-sentence-term arb="هاذا" eng="this.M" :term="$terms['hāda'] ?? null"/>
                    <x-sentence-term arb="ولد" eng="child" :term="$terms['walad'] ?? null"/>
                </x-sentence-item>
            </div>

            <p>At first, this means you will need to memorize the plural forms of nouns one-by-one.
                However, <b>broken plurals</b> are ultimately patterns that roots fall into; the more you are exposed to
                them,
                the more easily you will be able to instinctively predict the <b>broken plural</b> forms
                of nouns simply by ear.</p>

            <p>In English, you can theoretically pluralize anything by forcing an <b>"-s"</b> onto it. In Arabic,
                though,
                this is not possible — & there are many nouns that simply don't have plural forms at all, like the names
                of
                various foodstuffs. On the other hand, it's perfectly possible for nouns to fit into multiple <b>broken
                    plural</b> patterns & thus have multiple plural forms with no difference in meaning.</p>

            <x-vocabulary title="patterns">
                <x-term-item arb="12ā3" eng="(1v23 Broken Plural)"/>
                <x-term-item arb="12ū3" eng="(1v23 Broken Plural)"/>
            </x-vocabulary>
        </x-collapsible>

        {{--        <x-nav-modules--}}
        {{--            img="https://upload.wikimedia.org/wikipedia/commons/9/92/%D7%A4%D7%99%D7%A0%D7%92%27%D7%90%D7%9F_%D7%A1%D7%98%D7%95%D7%93%D7%99%D7%95_%D7%AA%D7%99%D7%9B%D7%95%D7%9F_%D7%A8%D7%95%D7%98%D7%91%D7%A8%D7%92.jpg"--}}
        {{--            position="80%"/>--}}
        <x-nav-modules
            img="https://upload.wikimedia.org/wikipedia/commons/b/bf/Palamuse_kihelkonnakooli_klassiruum.jpg"
            position="30%"/>
        <div x-data="{ tab: 'm1' }" @tab-changed.window="tab = $event.detail" class="tabs">
            @foreach (range(1, 3) as $m)
                <div x-show="tab === 'm{{ $m }}'">
                    @include('lessons.u1.l2.m' . $m, ['u' => 1, 'l' => 2, 'm' => $m, 'terms' => $terms, 'sentences' => $sentences])
                </div>
            @endforeach
        </div>
    </x-lesson>

@endsection
