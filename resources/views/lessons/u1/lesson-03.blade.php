@extends ('layouts.main')

@section('content')

    <x-lesson unit="1" lesson="3">

        <x-collapsible title="{{ __('know-this') }} | لازم تكون عارف">
            <h1>active participles</h1>

            <p>In Arabic, there is a type of adjective derived from verbs known as the <b>Active Participle</b>.
                All the
                <b>Active Participles</b> we will learn for now are formed by placing the root in the <b>فاعل
                    (fāʕil)</b>
                pattern — but not all terms in this pattern are necessarily <b>Active
                    Participles</b>. Some adjectives — like <b>شاطر (šātir "smart")</b> — are in the pattern,
                but are not <b>Active
                    Participles</b>, as they are not derived from verbs. Other terms in the pattern — like <b>طالب
                    (ṭālib "student")</b> — are not adjectives at all.</p>

            <p>The <b>Active Participle</b> denotes the state that results from a subject carrying out an action or
                undergoing a
                process. In some ways, the Arabic <b>Active Participle</b> is similar to the English <b>Present
                    Participle</b>
                (i.e.
                <b>"-ing"</b> words), as they both refer to the current state of the subject. But while the <b>Present
                    Participle</b> denotes an ongoing action, the <b>Active Participle</b>
                denotes the state that results from a completed action.</p>

            <div class="array">
                <x-sentence eng="he's standing">
                    <x-sentence-term arb="هو" eng="he" :term="$terms['huwwe'] ?? null"/>
                    <x-sentence-term arb="واقف" eng="standing" :term="$terms['wāʔif'] ?? null"/>
                </x-sentence>
                <x-sentence eng="he stood up">
                    <x-sentence-term arb="هو" eng="he" :term="$terms['huwwe'] ?? null"/>
                    <x-sentence-term arb="وقف" eng="3M.stood" :term="$terms['wiʔif'] ?? null"/>
                </x-sentence>
            </div>
            <x-sentence eng="he's standing on the corner">
                <x-sentence-term arb="هو" eng="he" :term="$terms['huwwe'] ?? null"/>
                <x-sentence-term arb="واقف" eng="standing" :term="$terms['ʔāʕid'] ?? null"/>
                <x-sentence-term arb="عـ" eng="on" :term="$terms['ʔala'] ?? null"/>
                <x-sentence-term arb="الكوربا" eng="the-corner" :term="$terms['kursy'] ?? null"/>
            </x-sentence>

            <div class="array">
                <x-sentence eng="he's seated">
                    <x-sentence-term arb="هو" eng="he" :term="$terms['huwwe'] ?? null"/>
                    <x-sentence-term arb="قاعد" eng="seated" :term="$terms['ʔāʕid'] ?? null"/>
                </x-sentence>
                <x-sentence eng="he sat down">
                    <x-sentence-term arb="هو" eng="he" :term="$terms['huwwe'] ?? null"/>
                    <x-sentence-term arb="قعد" eng="3M.sat" :term="$terms['ʔaʕad'] ?? null"/>
                </x-sentence>
            </div>
            <x-sentence eng="he's seated on the chair">
                <x-sentence-term arb="هو" eng="he" :term="$terms['huwwe'] ?? null"/>
                <x-sentence-term arb="قاعد" eng="seated" :term="$terms['ʔāʕid'] ?? null"/>
                <x-sentence-term arb="عـ" eng="on" :term="$terms['ʔala'] ?? null"/>
                <x-sentence-term arb="الكرسي" eng="the-chair" :term="$terms['kursy'] ?? null"/>
            </x-sentence>

            <p>Insofar as they are states rather than progressive actions, <b>Active Participles</b> are often very
                similar in meaning to generic adjectives, but are used in a greater variety of situations, lending
                themselves to various verb-like usages that will be discussed throughout the course.</p>

            <x-vocabulary title="patterns">
                <x-term arb="1ā2i3" eng="(Form 1 Active Participle)"/>
            </x-vocabulary>
        </x-collapsible>

        <x-nav-modules
            img="https://upload.wikimedia.org/wikipedia/commons/3/3e/Nazareth_Panorama_Dafna_Tal_IMOT_%2814532097313%29.jpg"
            position="60%"/>
        <div x-data="{ tab: 'm1' }" @tab-changed.window="tab = $event.detail" class="tabs">
            @foreach (range(1, 3) as $m)
                <div x-show="tab === 'm{{ $m }}'">
                    @include('lessons.u1.l3.m' . $m, ['u' => 1, 'l' => 3, 'm' => $m, 'terms' => $terms, 'sentences' => $sentences])
                </div>
            @endforeach
        </div>
    </x-lesson>

@endsection
