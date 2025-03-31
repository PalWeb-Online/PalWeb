<x-page-head title="{{ __('education') }}" blurb="Navigate the academic world with ease! Discover essential vocabulary for school & university so
    you can talk about all your favorite subjects.">
    <x-link :href="route('explore.index')">{{ __('explore') }}</x-link>
    <x-link :href="route('explore.show', 'education')">{{ __('education') }}</x-link>
</x-page-head>

<div class="wiki-container">
    <x-vocabulary title="going to school">
        <x-term-item :term="$terms->firstWhere('translit', 'madrase')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħaḍāne')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'bustān')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'tamhīdi')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'tawžīhi')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṭālib')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'daras')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'šāṭir')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕabqari')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'fahmān')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'mrakkiz')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'tʕallam')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'mʕallim')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕallam')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṣaff')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħiṣṣa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'dars')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'dirāse')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔimtiħān')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕalāme')"/>
    </x-vocabulary>
    <x-vocabulary title="school supplies">
        <x-term-item :term="$terms->firstWhere('translit', 'šanṭa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'daftar')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔalam')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'maʔaṣṣ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'wāžib')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'lōħ')"/>
    </x-vocabulary>
    <x-vocabulary title="going to university">
        <x-term-item :term="$terms->firstWhere('translit', 'žāmʕa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'kulliyye')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔustāz')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'faṣl')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔižāze')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'baħs')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕarḍ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'mašrūʕ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'risāle')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'šahāde')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'txarraž')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'taxaṣṣuṣ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'mutaxaṣṣiṣ')"/>
    </x-vocabulary>
    <x-vocabulary title="academic subjects">
        <x-term-item :term="$terms->firstWhere('translit', 'mādde')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔadabi')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕilmi')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'tārīx')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'falsafe')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħaʔʔ')"/>
        <x-term-item subterm arb="حقوق" eng="law, jurisprudence (field of study)"/>
        <x-term-item :term="$terms->firstWhere('translit', 'muħāsabe')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔiqtiṣād')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕilm')"/>
        <x-term-item subterm arb="علوم" eng="science"/>
        <x-term-item :term="$terms->firstWhere('translit', 'handase')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'barmaže')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'riyāḍiyyāt')"/>
    </x-vocabulary>
</div>
