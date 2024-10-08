@extends ('layouts.main')

@section('content')

    <x-lesson unit="2" lesson="3">

        <x-collapsible title="{{ __('know-this') }} | لازم تكون عارف">
            <h1>collective nouns</h1>

            <p>Some Arabic nouns refer to the idea of something, rather than a single instance of it. In English, some terms for
                substances — like <b>"paper"</b> — can't be counted, so they must be qualified by a counter (e.g. <b>"a sheet of
                    paper"</b>). In Arabic, these types of terms — <b>collective nouns</b> — are far more common; many plants,
                animals, substances & foodstuffs are referred to by collective nouns.</p>
            <x-sentence-item eng="paper">
                <x-sentence-term arb="ورق" eng="(coll.) paper" :term="$terms['waraʔ'] ?? null"/>
            </x-sentence-item>
            <p>While the counter used for these mass nouns in English is not really predictable, in Arabic we always form <b>singulative
                    nouns</b> by adding an <b>ة (-e)</b> to the collective noun.</p>
            <x-sentence-item eng="a sheet of paper">
                <x-sentence-term arb="ورقة" eng="(sing.) paper" :term="$terms['waraʔ'] ?? null"/>
            </x-sentence-item>
            <p>As you can see, singulative nouns are always <b>feminine singular</b>, while collective nouns are always <b>masculine
                    singular</b>. It may seem strange for a collective noun to be singular, but these types of terms are
                singular in English as well (e.g. <b>"paper is white"</b>). However, where English doesn't use a mass noun, the
                singular nature of the Arabic collective noun doesn't always translate well.</p>
        </x-collapsible>

        <x-nav-modules
            img="https://upload.wikimedia.org/wikipedia/commons/d/d6/Knafeh_in_Jaffa_cafe.jpg"
            position="20%"/>
        <div x-data="{ tab: 'm1' }" @tab-changed.window="tab = $event.detail" class="tabs">
            @foreach (range(1, 3) as $m)
                <div x-show="tab === 'm{{ $m }}'">
                    @include('lessons.u2.l3.m' . $m, ['u' => 2, 'l' => 3, 'm' => $m, 'terms' => $terms, 'sentences' => $sentences])
                </div>
            @endforeach
        </div>
    </x-lesson>

@endsection
