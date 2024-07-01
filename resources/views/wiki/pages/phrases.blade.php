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
        <x-term :term="$terms->firstWhere('translit', 'marħaba')"/>
        <x-term :term="$terms->firstWhere('translit', 'hala')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔahla')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔahla w-sahla')"/>
        <x-term :term="$terms->firstWhere('translit', 'ṣabāħo')"/>
        <x-term :term="$terms->firstWhere('translit', 'ṣabāħ l-xēr')"/>
        <x-term :term="$terms->firstWhere('translit', 'masa l-xēr')"/>
        <x-term :term="$terms->firstWhere('translit', 'tiṣbaħ ʕa xēr')"/>
        <x-term :term="$terms->firstWhere('translit', 'kīf l-ħāl')"/>
        <x-term :term="$terms->firstWhere('translit', 'kīf l-waḍʕ')"/>
        <x-term :term="$terms->firstWhere('translit', 'salāmāt')"/>
        <x-term :term="$terms->firstWhere('translit', 'maʕ s-salāme')"/>
        <x-term :term="$terms->firstWhere('translit', 'tūṣal bis-salāme')"/>
        <x-term :term="$terms->firstWhere('translit', 'ħamdillah ʕas-salāme')"/>
        <x-term :term="$terms->firstWhere('translit', 'tšarrafna')"/>
        <x-term :term="$terms->firstWhere('translit', 'furṣa saʕīde')"/>
        <x-term :term="$terms->firstWhere('translit', 'nbasaṭt b-maʕriftak')"/>
    </x-vocabulary>

    <x-vocabulary title="{{ __('well-wishes') }}">
        <x-term :term="$terms->firstWhere('translit', 'yisʕid')"/>
        <x-term :term="$terms->firstWhere('translit', 'ṣaħħa')"/>
        <x-term :term="$terms->firstWhere('translit', 'mabrūk')"/>
        <x-term :term="$terms->firstWhere('translit', 'naʕīman')"/>
        <x-term :term="$terms->firstWhere('translit', 'yaʕṭīk l-ʕāfye')"/>
        <x-term :term="$terms->firstWhere('translit', 'bit-tawfīʔ')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔallah yirħamo')"/>
        <x-term :term="$terms->firstWhere('translit', 'kull sane w-ʔinta sālim')"/>
        <x-term :term="$terms->firstWhere('translit', 'kull ʕām w-ʔinta b-xēr')"/>
        <x-term :term="$terms->firstWhere('translit', 'dōm')"/>
    </x-vocabulary>

    <x-vocabulary title="{{ __('discourse-markers') }}">
        <x-term :term="$terms->firstWhere('translit', 'naʕm')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔāh')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔaywa')"/>
        <x-term :term="$terms->firstWhere('translit', 'ħāḍir')"/>
        <x-term :term="$terms->firstWhere('translit', 'mbala')"/>
        <x-term :term="$terms->firstWhere('translit', 'mahū')"/>
        <x-term :term="$terms->firstWhere('translit', 'laʔ')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔizan')"/>
        <x-term :term="$terms->firstWhere('translit', 'la-kān')"/>
        <x-term :term="$terms->firstWhere('translit', 'l-muhimm')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʕa fikra')"/>
        <x-term :term="$terms->firstWhere('translit', 'b-ġaḍḍ n-naẓar')"/>
        <x-term :term="$terms->firstWhere('translit', 'b-kull l-ʔaħwāl')"/>
        <x-term :term="$terms->firstWhere('translit', 'b-nihāyet l-ʔamr')"/>
        <x-term :term="$terms->firstWhere('translit', 'min bāb l-fuḍūl')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔiza biddak ṣ-ṣarāħa')"/>
    </x-vocabulary>

    <x-vocabulary title="{{ __('interjections') }}">
        <x-term :term="$terms->firstWhere('translit', 'yaʕni')"/>
        <x-term :term="$terms->firstWhere('translit', 'yalla')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔallāh')"/>
        <x-term :term="$terms->firstWhere('translit', 'ya ʔallah')"/>
        <x-term :term="$terms->firstWhere('translit', 'ya salām')"/>
        <x-term :term="$terms->firstWhere('translit', 'ya wēli')"/>
        <x-term :term="$terms->firstWhere('translit', 'ya rabb')"/>
        <x-term :term="$terms->firstWhere('translit', 'ya ħarām')"/>
        <x-term :term="$terms->firstWhere('translit', 'ya xasāra')"/>
        <x-term :term="$terms->firstWhere('translit', 'yixti')"/>
        <x-term :term="$terms->firstWhere('translit', 'yamma')"/>
        <x-term :term="$terms->firstWhere('translit', 'yi')"/>
        <x-term :term="$terms->firstWhere('translit', 'yaʕ')"/>
        <x-term :term="$terms->firstWhere('translit', 'yāy')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔaxx')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔaff')"/>
        <x-term :term="$terms->firstWhere('translit', 'šukran')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʕafwan')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʕala rāsi')"/>
        <x-term :term="$terms->firstWhere('translit', 'min zōʔak')"/>
        <x-term :term="$terms->firstWhere('translit', 'tislam')"/>
        <x-term :term="$terms->firstWhere('translit', 'yislamu ʔīdēk')"/>
        <x-term :term="$terms->firstWhere('translit', 'wala yhimmak')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔaġallbak')"/>
        <x-term :term="$terms->firstWhere('translit', 'law samaħt')"/>
        <x-term :term="$terms->firstWhere('translit', 'tfaḍḍal')"/>
        <x-term :term="$terms->firstWhere('translit', 'nyāl-')"/>
        <x-term :term="$terms->firstWhere('translit', 'ya rēt')"/>
        <x-term :term="$terms->firstWhere('translit', 'nšallah')"/>
        <x-term :term="$terms->firstWhere('translit', 'mašallah')"/>
        <x-term :term="$terms->firstWhere('translit', 'ħamdillah')"/>
        <x-term :term="$terms->firstWhere('translit', 'ħayāt ʔallah')"/>
        <x-term :term="$terms->firstWhere('translit', 'subħān ʔallāh')"/>
        <x-term :term="$terms->firstWhere('translit', 'lā samaħ ʔallāh')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔallah satar')"/>
        <x-term :term="$terms->firstWhere('translit', 'ṭabb')"/>
        <x-term :term="$terms->firstWhere('translit', 'ṭayyib')"/>
        <x-term :term="$terms->firstWhere('translit', 'miš ġalaṭ')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔižāk')"/>
        <x-term :term="$terms->firstWhere('translit', 'bass')"/>
        <x-term :term="$terms->firstWhere('translit', 'xalaṣ')"/>
        <x-term :term="$terms->firstWhere('translit', 'balāš')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʕēb')"/>
        <x-term :term="$terms->firstWhere('translit', 'wa-law')"/>
        <x-term :term="$terms->firstWhere('translit', 'wa-lak')"/>
        <x-term :term="$terms->firstWhere('translit', 'wāl')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʕādi')"/>
        <x-term :term="$terms->firstWhere('translit', 'maʕlēš')"/>
        <x-term :term="$terms->firstWhere('translit', 'basīṭa')"/>
        <x-term :term="$terms->firstWhere('translit', 'wallah')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʕan žadd')"/>
        <x-term :term="$terms->firstWhere('translit', 'maʕʔūl')"/>
        <x-term :term="$terms->firstWhere('translit', 'b-šarafi')"/>
        <x-term :term="$terms->firstWhere('translit', 'wala yumkin')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔaẓonn')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔabṣar')"/>
        <x-term :term="$terms->firstWhere('translit', 'w-baʕdēn')"/>
        <x-term :term="$terms->firstWhere('translit', 'kiza w-kiza')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔallah yiʕlam')"/>
        <x-term :term="$terms->firstWhere('translit', 'šu biʕarrefni')"/>
    </x-vocabulary>

    <x-vocabulary title="{{ __('other') }}">
        <x-term :term="$terms->firstWhere('translit', 'māl-')"/>
        <x-term :term="$terms->firstWhere('translit', 'bil-miyye')"/>
    </x-vocabulary>

    <p>Here are some additional common phrases that are non-idiomatic:</p>
    <x-vocabulary title="non-idiomatic">
        <x-term arb="إسمع" eng="yo, hey, listen"/>
        <x-term arb="ع راحتك" eng="take your time"/>
        <x-term arb="إذا بدّك الصراحة" eng="to be honest"/>
        <x-term arb="بشكل عامّ" eng="in general"/>
        <x-term arb="مش شرط" eng="not necessarily"/>
        <x-term arb="مش طبيعيّ" eng="it's abnormal; it's crazy"/>
        <x-term arb="بالقصد" eng="on purpose"/>
        <x-term arb="على علمي" eng="as far as I know"/>
    </x-vocabulary>
</div>
