<x-page-head title="{{ __('plants') }}" blurb="Get ready for the farmer's market or a hike through the Palestinian hills by leafing through
    words for common trees, herbs, fruits & vegetables.">
    <x-link :href="route('explore.index')">{{ __('explore') }}</x-link>
    <x-link :href="route('explore.show', 'plants')">{{ __('plants') }}</x-link>
</x-page-head>

<div class="doc-section">
    <x-vocabulary title="plants">
        <x-term-item :term="$terms->firstWhere('translit', 'nabāt')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'waraʔ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'zahr')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ward')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħadīqa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'bizr')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'šurš')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'šažar')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'zatūn')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ballūṭ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṣnōbar')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṣabbār')"/>
    </x-vocabulary>
    <x-vocabulary title="fruits">
        <x-term-item :term="$terms->firstWhere('translit', 'fawākih')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'tuffāħ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'nžāṣ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'lamūn')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'burdʔān')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'xōx')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'mōz')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕinb')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'tūt')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'frāwle')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'baṭṭīx')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'šimmām')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'rummān')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'tīn')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'tamr')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'manga')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔanānās')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'žōz hind')"/>
    </x-vocabulary>
    <x-vocabulary title="vegetables">
        <x-term-item :term="$terms->firstWhere('translit', 'ħisbe')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'xuḍār')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'xuḍarži')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'xass')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'žaržīr')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'sabānix')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'baʔdūnis')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'kuzbara')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'bētinžān')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'bandōra')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'filfil')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'bāmye')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'baṣal')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'xyār')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'fižl')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'banžar')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'žazar')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'baṭāṭa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'zanžabīl')"/>
    </x-vocabulary>
    <x-vocabulary title="herbs">
        <x-term-item :term="$terms->firstWhere('translit', 'ʕušb')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'rīħān')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'naʕnaʕ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'maramiyye')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'zaʕtar')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'summāʔ')"/>
    </x-vocabulary>
    <x-vocabulary title="legumes">
        <x-term-item :term="$terms->firstWhere('translit', 'fūl')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕadas')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħummuṣ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'fāṣūlya')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'bāzilla')"/>
    </x-vocabulary>
</div>
