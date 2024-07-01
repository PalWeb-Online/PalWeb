@extends ('layouts.main')

@section('content')

    <x-lesson unit="2" lesson="1">

        <x-collapsible title="{{ __('know-this') }} | لازم تكون عارف">
            <h1>the tā marbūṭa</h1>
            <x-sentence eng="a small bottle">
                <x-sentence-term arb="قنّينة" eng="bottle" :term="$terms['ʔannīne'] ?? null"/>
                <x-sentence-term arb="زغيرة" eng="small" :term="$terms['zḡīr'] ?? null"/>
            </x-sentence>
            <x-sentence eng="a bottle of water">
                <x-sentence-term arb="قنّينة" eng="bottle" :term="$terms['ʔannīne'] ?? null"/>
                <x-sentence-term arb="ميّ" eng="water" :term="$terms['mayy'] ?? null"/>
            </x-sentence>

            <p>Additionally, there is a distinct <b>sound plural</b> associated with feminine forms: <b>ات (-āt)</b>.
                Many nouns
                — especially feminine ones ending in <b>ة (-e)</b> — are pluralized this way. It's possible to
                pluralize feminine adjectives this way too, but feminine plural adjectives are grammatically unnecessary
                in
                Palestinian Arabic, as the plural is not gendered; non-feminine plural adjectives are already
                gender-neutral.
            </p>
            <x-inflections
                conjF="مشغولة" conjFtr="mašġūle"
                conjP="مشغولات" conjPtr="mašġūlāt"
            ></x-inflections>

            <x-vocabulary title="affixes">
                <x-term :term="$terms['-āt'] ?? null" i="1"/>
            </x-vocabulary>

            <x-vocabulary title="patterns">
                <x-term arb="1v2a3" eng="(1v23e Broken Plural)"/>
            </x-vocabulary>
        </x-collapsible>

        <x-nav-modules
            img="https://upload.wikimedia.org/wikipedia/commons/1/14/Construction_workers_in_Iran_09.jpg"
            position="10%"/>
        <div x-data="{ tab: 'm1' }" @tab-changed.window="tab = $event.detail" class="tabs">
            @foreach (range(1, 3) as $m)
                <div x-show="tab === 'm{{ $m }}'">
                    @include('lessons.u2.l1.m' . $m, ['u' => 2, 'l' => 1, 'm' => $m, 'terms' => $terms, 'sentences' => $sentences])
                </div>
            @endforeach
        </div>
    </x-lesson>

@endsection
