<x-page-head title="{{ __('education') }}" blurb="Navigate the academic world with ease! Discover essential vocabulary for school & university so
    you can talk about all your favorite subjects.">
    <x-link :href="route('explore.index')">{{ __('explore') }}</x-link>
    <x-link :href="route('explore.show', 'education')">{{ __('education') }}</x-link>
</x-page-head>

<div class="doc-section">
    <x-vocabulary title="going to school">
        <x-term :term="$terms->firstWhere('translit', 'madrase')"/>
        <x-term :term="$terms->firstWhere('translit', 'ħaḍāne')"/>
        <x-term :term="$terms->firstWhere('translit', 'bustān')"/>
        <x-term :term="$terms->firstWhere('translit', 'tamhīdi')"/>
        <x-term :term="$terms->firstWhere('translit', 'tawžīhi')"/>
        <x-term :term="$terms->firstWhere('translit', 'ṭālib')"/>
        <x-term :term="$terms->firstWhere('translit', 'daras')"/>
        <x-term :term="$terms->firstWhere('translit', 'šāṭir')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʕabqari')"/>
        <x-term :term="$terms->firstWhere('translit', 'fahmān')"/>
        <x-term :term="$terms->firstWhere('translit', 'mrakkiz')"/>
        <x-term :term="$terms->firstWhere('translit', 'tʕallam')"/>
        <x-term :term="$terms->firstWhere('translit', 'mʕallim')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʕallam')"/>
        <x-term :term="$terms->firstWhere('translit', 'ṣaff')"/>
        <x-term :term="$terms->firstWhere('translit', 'ħiṣṣa')"/>
        <x-term :term="$terms->firstWhere('translit', 'dars')"/>
        <x-term :term="$terms->firstWhere('translit', 'dirāse')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔimtiħān')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʕalāme')"/>
    </x-vocabulary>
    <x-vocabulary title="school supplies">
        <x-term :term="$terms->firstWhere('translit', 'šanṭa')"/>
        <x-term :term="$terms->firstWhere('translit', 'daftar')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔalam')"/>
        <x-term :term="$terms->firstWhere('translit', 'maʔaṣṣ')"/>
        <x-term :term="$terms->firstWhere('translit', 'wāžib')"/>
        <x-term :term="$terms->firstWhere('translit', 'lōħ')"/>
    </x-vocabulary>
    <x-vocabulary title="going to university">
        <x-term :term="$terms->firstWhere('translit', 'žāmʕa')"/>
        <x-term :term="$terms->firstWhere('translit', 'kulliyye')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔustāz')"/>
        <x-term :term="$terms->firstWhere('translit', 'faṣl')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔižāze')"/>
        <x-term :term="$terms->firstWhere('translit', 'baħs')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʕarḍ')"/>
        <x-term :term="$terms->firstWhere('translit', 'mašrūʕ')"/>
        <x-term :term="$terms->firstWhere('translit', 'risāle')"/>
        <x-term :term="$terms->firstWhere('translit', 'šahāde')"/>
        <x-term :term="$terms->firstWhere('translit', 'txarraž')"/>
        <x-term :term="$terms->firstWhere('translit', 'taxaṣṣuṣ')"/>
        <x-term :term="$terms->firstWhere('translit', 'mutaxaṣṣiṣ')"/>
    </x-vocabulary>
    <x-vocabulary title="academic subjects">
        <x-term :term="$terms->firstWhere('translit', 'mādde')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔadabi')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʕilmi')"/>
        <x-term :term="$terms->firstWhere('translit', 'tārīx')"/>
        <x-term :term="$terms->firstWhere('translit', 'falsafe')"/>
        <x-term :term="$terms->firstWhere('translit', 'ħaʔʔ')"/>
        <x-term subterm arb="حقوق" eng="law, jurisprudence (field of study)"/>
        <x-term :term="$terms->firstWhere('translit', 'muħāsabe')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔiqtiṣād')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʕilm')"/>
        <x-term subterm arb="علوم" eng="science"/>
        <x-term :term="$terms->firstWhere('translit', 'handase')"/>
        <x-term :term="$terms->firstWhere('translit', 'barmaže')"/>
        <x-term :term="$terms->firstWhere('translit', 'riyāḍiyyāt')"/>
    </x-vocabulary>
</div>
