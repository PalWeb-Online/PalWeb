<x-page-head title="{{ __('phrases') }}" blurb="Phrases include greetings, interjections, discourse markers & similar terms that are generally
    syntax-independent.">
    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>
    <x-link :href="route('wiki.show', 'syntax')">{{ __('syntax') }}</x-link>
    <x-link :href="route('wiki.show', 'phrases')">{{ __('phrases') }}</x-link>
</x-page-head>

<x-tip>
    <p>Other types of terms that may be often used as non-idiomatic interjections are not repeated here. Also,
        proverbial expressions are generally not included in the Dictionary.</p>
</x-tip>

<div class="doc-section">
    <h1>{{ __('inventory') }}</h1>
    <x-vocabulary title="{{ __('greetings') }}">
        <x-term-item :term="$terms->firstWhere('translit', 'marħaba')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'hala')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔahla')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔahla w-sahla')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṣabāħo')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṣabāħ l-xēr')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'masa l-xēr')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'tiṣbaħ ʕa xēr')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'kīf l-ħāl')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'kīf l-waḍʕ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'salāmāt')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'maʕ s-salāme')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'tūṣal bis-salāme')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħamdillah ʕas-salāme')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'tšarrafna')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'furṣa saʕīde')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'nbasaṭt b-maʕriftak')"/>
    </x-vocabulary>

    <x-vocabulary title="{{ __('well-wishes') }}">
        <x-term-item :term="$terms->firstWhere('translit', 'yisʕid')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṣaħħa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'mabrūk')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'naʕīman')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'yaʕṭīk l-ʕāfye')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'bit-tawfīʔ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔallah yirħamo')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'kull sane w-ʔinta sālim')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'kull ʕām w-ʔinta b-xēr')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'dōm')"/>
    </x-vocabulary>

    <x-vocabulary title="{{ __('discourse-markers') }}">
        <x-term-item :term="$terms->firstWhere('translit', 'naʕm')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔāh')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔaywa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħāḍir')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'mbala')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'mahū')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'laʔ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔizan')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'la-kān')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'l-muhimm')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕa fikra')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'b-ġaḍḍ n-naẓar')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'b-kull l-ʔaħwāl')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'b-nihāyet l-ʔamr')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'min bāb l-fuḍūl')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔiza biddak ṣ-ṣarāħa')"/>
    </x-vocabulary>

    <x-vocabulary title="{{ __('interjections') }}">
        <x-term-item :term="$terms->firstWhere('translit', 'yaʕni')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'yalla')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔallāh')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ya ʔallah')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ya salām')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ya wēli')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ya rabb')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ya ħarām')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ya xasāra')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'yixti')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'yamma')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'yi')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'yaʕ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'yāy')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔaxx')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔaff')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'šukran')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕafwan')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕala rāsi')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'min zōʔak')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'tislam')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'yislamu ʔīdēk')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'wala yhimmak')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔaġallbak')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'law samaħt')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'tfaḍḍal')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'nyāl-')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ya rēt')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'nšallah')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'mašallah')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħamdillah')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħayāt ʔallah')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'subħān ʔallāh')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'lā samaħ ʔallāh')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔallah satar')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṭabb')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṭayyib')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'miš ġalaṭ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔižāk')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'bass')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'xalaṣ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'balāš')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕēb')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'wa-law')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'wa-lak')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'wāl')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕādi')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'maʕlēš')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'basīṭa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'wallah')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕan žadd')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'maʕʔūl')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'b-šarafi')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'wala yumkin')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔaẓonn')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔabṣar')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'w-baʕdēn')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'kiza w-kiza')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔallah yiʕlam')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'šu biʕarrefni')"/>
    </x-vocabulary>

    <x-vocabulary title="{{ __('other') }}">
        <x-term-item :term="$terms->firstWhere('translit', 'māl-')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'bil-miyye')"/>
    </x-vocabulary>

    <p>Here are some additional common phrases that are non-idiomatic:</p>
    <x-vocabulary title="non-idiomatic">
        <x-term-item arb="إسمع" eng="yo, hey, listen"/>
        <x-term-item arb="ع راحتك" eng="take your time"/>
        <x-term-item arb="إذا بدّك الصراحة" eng="to be honest"/>
        <x-term-item arb="بشكل عامّ" eng="in general"/>
        <x-term-item arb="مش شرط" eng="not necessarily"/>
        <x-term-item arb="مش طبيعيّ" eng="it's abnormal; it's crazy"/>
        <x-term-item arb="بالقصد" eng="on purpose"/>
        <x-term-item arb="على علمي" eng="as far as I know"/>
    </x-vocabulary>
</div>
