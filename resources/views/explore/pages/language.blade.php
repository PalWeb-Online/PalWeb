<x-page-head title="{{ __('language') }}" blurb="Unlock the literary treasures of language! Delve into a realm of words, exploring expressions
    that shape stories and culture.">
    <x-link :href="route('explore.index')">{{ __('explore') }}</x-link>
    <x-link :href="route('explore.show', 'language')">{{ __('language') }}</x-link>
</x-page-head>

<div class="doc-section">
    <x-vocabulary title="linguistics">
        <x-term :term="$terms->firstWhere('translit', 'kilme')"/>
        <x-term :term="$terms->firstWhere('translit', 'žumle')"/>
        <x-term :term="$terms->firstWhere('translit', 'ħarf')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔabžad')"/>
        <x-term :term="$terms->firstWhere('translit', 'luġa')"/>
        <x-term :term="$terms->firstWhere('translit', 'lahže')"/>
        <x-term :term="$terms->firstWhere('translit', 'lafẓ')"/>
        <x-term :term="$terms->firstWhere('translit', 'fiʕl')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔism')"/>
        <x-term :term="$terms->firstWhere('translit', 'ṣifa')"/>
        <x-term :term="$terms->firstWhere('translit', 'qawāʕid')"/>
        <x-term :term="$terms->firstWhere('translit', 'mufradāt')"/>
        <x-term :term="$terms->firstWhere('translit', 'taržam')"/>
        <x-term :term="$terms->firstWhere('translit', 'taržame')"/>
    </x-vocabulary>
    <x-vocabulary title="telling stories">
        <x-term :term="$terms->firstWhere('translit', 'ʔāl')"/>
        <x-term :term="$terms->firstWhere('translit', 'kalām')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔuṣṣa')"/>
        <x-term :term="$terms->firstWhere('translit', 'ħiwār')"/>
        <x-term :term="$terms->firstWhere('translit', 'muħādase')"/>
        <x-term :term="$terms->firstWhere('translit', 'ħaka')"/>
        <x-term :term="$terms->firstWhere('translit', 'ħaky')"/>
        <x-term :term="$terms->firstWhere('translit', 'ħkāye')"/>
        <x-term :term="$terms->firstWhere('translit', 'xurāfe')"/>
    </x-vocabulary>
    <x-vocabulary title="literature">
        <x-term :term="$terms->firstWhere('translit', 'ʔadab')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔara')"/>
        <x-term :term="$terms->firstWhere('translit', 'ktāb')"/>
        <x-term :term="$terms->firstWhere('translit', 'katab')"/>
        <x-term :term="$terms->firstWhere('translit', 'kātib')"/>
        <x-term :term="$terms->firstWhere('translit', 'maktabe')"/>
        <x-term :term="$terms->firstWhere('translit', 'maqāle')"/>
        <x-term :term="$terms->firstWhere('translit', 'žarīde')"/>
        <x-term :term="$terms->firstWhere('translit', 'mažalle')"/>
        <x-term :term="$terms->firstWhere('translit', 'riwāye')"/>
        <x-term :term="$terms->firstWhere('translit', 'šiʕr')"/>
        <x-term :term="$terms->firstWhere('translit', 'šāʕir')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔusṭūra')"/>
        <x-term :term="$terms->firstWhere('translit', 'šaxṣiyye')"/>
    </x-vocabulary>
</div>
