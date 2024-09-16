@extends ('layouts.main')

@section('content')

    <x-lesson unit="2" lesson="2">

        <x-collapsible title="{{ __('know-this') }} | لازم تكون عارف">
            <h1>pseudo-verbs</h1>

            <p>Although we haven't officially learned how to use verbs in Arabic, we've already learned how to create a
                variety of sentence types. In <b>Unit 1</b>, we learned how to create <b>nominal sentences</b> (i.e.
                <b>"it is"</b> sentences), using <b>Active Participles</b> to produce a variety of verb-like meanings.
            </p>
            <x-sentence-item eng="Jerusalem is in Palestine">
                <x-sentence-term arb="القدس" eng="Jerusalem" :term="$terms['l-ʔuds'] ?? null"/>
                <x-sentence-term arb="∅" eng="(is)"/>
                <x-sentence-term arb="بـ" eng="in" :term="$terms['b-'] ?? null"/>
                <x-sentence-term arb="ـفلسطين" eng="Palestine" :term="$terms['falasṭīn'] ?? null"/>
            </x-sentence-item>
            <x-sentence-item eng="he is going to the hospital">
                <x-sentence-term arb="هو" eng="he" :term="$terms['huwwe'] ?? null"/>
                <x-sentence-term arb="∅" eng="(is)"/>
                <x-sentence-term arb="رايح" eng="going" :term="$terms['rāyiħ'] ?? null"/>
                <x-sentence-term arb="عـ" eng="to" :term="$terms['ʕala'] ?? null"/>
                <x-sentence-term arb="ـالمستشفى" eng="the-hospital" :term="$terms['mustašfa'] ?? null"/>
            </x-sentence-item>
            <p>We also learned how to use <b>فيه (fīh)</b> to create <b>existential sentences</b> (i.e. <b>"there
                    is"</b> sentences). Since these are the two types of sentence in Arabic that feature a <b>null
                    copula</b>
                (i.e. the hidden <b>"is"</b>), they are known collectively as <b>copular sentences</b>.
            </p>

            <x-sentence-item eng="there is trash in the bag">
                <x-sentence-term arb="فيه" eng="in-it" :term="$terms['fīh'] ?? null"/>
                <x-sentence-term arb="∅" eng="(is)"/>
                <x-sentence-term arb="زبالة" eng="trash" :term="$terms['zbāle'] ?? null"/>
                <x-sentence-term arb="بـ" eng="in" :term="$terms['b-'] ?? null"/>
                <x-sentence-term arb="ـالكيس" eng="the-bag" :term="$terms['kīs'] ?? null"/>
            </x-sentence-item>

            <p>And there's a lot we can say using just these two types of sentence, as a variety of nouns & prepositions
                are used in Palestinian Arabic where a verb might be expected in English. Although the sentences they
                yield are essentially <b>verbal sentences</b>, these <b>pseudo-verbs</b> are inflected with clitic
                pronouns, meaning we can start using them without learning anything new.</p>
            <div class="array">
                <x-sentence-item eng="he seems upset">
                    <x-sentence-term arb="شكله" eng="3M.seems" :term="$terms['šiklo'] ?? null"/>
                    <x-sentence-term arb="زعلان" eng="upset" :term="$terms['zaʕlān'] ?? null"/>
                </x-sentence-item>
                <x-sentence-item eng="he seems">
                    <x-sentence-term arb="شكلـ" eng="shape" :term="$terms['šikl'] ?? null"/>
                    <x-sentence-term arb="ـه" eng="his" :term="$terms['-o'] ?? null"/>
                </x-sentence-item>
            </div>

            <p>In <b>Unit 2</b>, we will be using the only two <b>"true" pseudo-verbs</b>. While other terms may be used
                similarly, only these two are formally idiomatic; that is, their meanings as <b>pseudo-verbs</b> are sui
                generis.
                Since the reanalysis of <b>nominal sentences</b> as <b>verbal sentences</b> relies on the use of clitic
                pronouns as inflections, these terms must necessarily be inflected to produce their verbal meanings.</p>

            <x-sentence-item eng="his father seems upset">
                <x-sentence-term arb="أبوه" eng="his-father" :term="$terms['ʔabu'] ?? null"/>
                <x-sentence-term arb="شكله" eng="3M.seems" :term="$terms['šiklo'] ?? null"/>
                <x-sentence-term arb="زعلان" eng="upset" :term="$terms['zaʕlān'] ?? null"/>
            </x-sentence-item>
            <x-sentence-item eng="? his father's shape is upset">
                <x-sentence-term arb="شكل" eng="shape" :term="$terms['šiklo'] ?? null"/>
                <x-sentence-term arb="أبوه" eng="his-father" :term="$terms['ʔabu'] ?? null"/>
                <x-sentence-term arb="∅" eng="(is)"/>
                <x-sentence-term arb="زعلان" eng="upset" :term="$terms['zaʕlān'] ?? null"/>
            </x-sentence-item>

            <p>Not all <b>pseudo-verbs</b> are necessarily used the same way. In <b>Unit 3</b>, we will learn about
                the five <b>"hybrid" pseudo-verbs</b>: these are specifically prepositions that, although they may be
                used like <b>"true" pseudo-verbs</b>, underlyingly build upon <b>existential sentences</b> rather than
                <b>nominal sentences</b>, permitting some idiosyncratic usages that will be addressed later. Regardless,
                both groups share the defining characteristics of <b>pseudo-verbs</b>: producing an idiomatic verbal
                meaning & being inflected by clitic pronouns.</p>
        </x-collapsible>

        <x-nav-modules
            img="https://upload.wikimedia.org/wikipedia/commons/4/49/Playing_dominoes%2C_Cairo.jpg"
            position="15%"/>
        <div x-data="{ tab: 'm1' }" @tab-changed.window="tab = $event.detail" class="tabs">
            @foreach (range(1, 3) as $m)
                <div x-show="tab === 'm{{ $m }}'">
                    @include('lessons.u2.l2.m' . $m, ['u' => 2, 'l' => 2, 'm' => $m, 'terms' => $terms, 'sentences' => $sentences])
                </div>
            @endforeach
        </div>
    </x-lesson>

@endsection
