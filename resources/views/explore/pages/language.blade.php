<x-page-head title="{{ __('language') }}" blurb="Unlock the literary treasures of language! Delve into a realm of words, exploring expressions
    that shape stories and culture.">
    <x-link :href="route('explore.index')">{{ __('explore') }}</x-link>
    <x-link :href="route('explore.show', 'language')">{{ __('language') }}</x-link>
</x-page-head>

<div class="wiki-content-block">
    <x-vocabulary title="linguistics">
        <x-term-item :term="$terms->firstWhere('translit', 'kilme')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'žumle')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħarf')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔabžad')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'luġa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'lahže')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'lafẓ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'fiʕl')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔism')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṣifa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'qawāʕid')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'mufradāt')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'taržam')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'taržame')"/>
    </x-vocabulary>
    <x-vocabulary title="telling stories">
        <x-term-item :term="$terms->firstWhere('translit', 'ʔāl')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'kalām')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔuṣṣa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħiwār')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'muħādase')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħaka')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħaky')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħkāye')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'xurāfe')"/>
    </x-vocabulary>
    <x-vocabulary title="literature">
        <x-term-item :term="$terms->firstWhere('translit', 'ʔadab')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔara')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ktāb')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'katab')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'kātib')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'maktabe')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'maqāle')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'žarīde')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'mažalle')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'riwāye')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'šiʕr')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'šāʕir')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔusṭūra')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'šaxṣiyye')"/>
    </x-vocabulary>
</div>
