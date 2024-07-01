<x-page-head title="{{ __('plants') }}" blurb="Get ready for the farmer's market or a hike through the Palestinian hills by leafing through
    words for common trees, herbs, fruits & vegetables.">
    <x-link :href="route('explore.index')">{{ __('explore') }}</x-link>
    <x-link :href="route('explore.show', 'plants')">{{ __('plants') }}</x-link>
</x-page-head>

<div class="doc-section">
    <x-vocabulary title="plants">
        <x-term :term="$terms->firstWhere('translit', 'nabāt')"/>
        <x-term :term="$terms->firstWhere('translit', 'waraʔ')"/>
        <x-term :term="$terms->firstWhere('translit', 'zahr')"/>
        <x-term :term="$terms->firstWhere('translit', 'ward')"/>
        <x-term :term="$terms->firstWhere('translit', 'ħadīqa')"/>
        <x-term :term="$terms->firstWhere('translit', 'bizr')"/>
        <x-term :term="$terms->firstWhere('translit', 'šurš')"/>
        <x-term :term="$terms->firstWhere('translit', 'šažar')"/>
        <x-term :term="$terms->firstWhere('translit', 'zatūn')"/>
        <x-term :term="$terms->firstWhere('translit', 'ballūṭ')"/>
        <x-term :term="$terms->firstWhere('translit', 'ṣnōbar')"/>
        <x-term :term="$terms->firstWhere('translit', 'ṣabbār')"/>
    </x-vocabulary>
    <x-vocabulary title="fruits">
        <x-term :term="$terms->firstWhere('translit', 'fawākih')"/>
        <x-term :term="$terms->firstWhere('translit', 'tuffāħ')"/>
        <x-term :term="$terms->firstWhere('translit', 'nžāṣ')"/>
        <x-term :term="$terms->firstWhere('translit', 'lamūn')"/>
        <x-term :term="$terms->firstWhere('translit', 'burdʔān')"/>
        <x-term :term="$terms->firstWhere('translit', 'xōx')"/>
        <x-term :term="$terms->firstWhere('translit', 'mōz')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʕinb')"/>
        <x-term :term="$terms->firstWhere('translit', 'tūt')"/>
        <x-term :term="$terms->firstWhere('translit', 'frāwle')"/>
        <x-term :term="$terms->firstWhere('translit', 'baṭṭīx')"/>
        <x-term :term="$terms->firstWhere('translit', 'šimmām')"/>
        <x-term :term="$terms->firstWhere('translit', 'rummān')"/>
        <x-term :term="$terms->firstWhere('translit', 'tīn')"/>
        <x-term :term="$terms->firstWhere('translit', 'tamr')"/>
        <x-term :term="$terms->firstWhere('translit', 'manga')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔanānās')"/>
        <x-term :term="$terms->firstWhere('translit', 'žōz hind')"/>
    </x-vocabulary>
    <x-vocabulary title="vegetables">
        <x-term :term="$terms->firstWhere('translit', 'ħisbe')"/>
        <x-term :term="$terms->firstWhere('translit', 'xuḍār')"/>
        <x-term :term="$terms->firstWhere('translit', 'xuḍarži')"/>
        <x-term :term="$terms->firstWhere('translit', 'xass')"/>
        <x-term :term="$terms->firstWhere('translit', 'žaržīr')"/>
        <x-term :term="$terms->firstWhere('translit', 'sabānix')"/>
        <x-term :term="$terms->firstWhere('translit', 'baʔdūnis')"/>
        <x-term :term="$terms->firstWhere('translit', 'kuzbara')"/>
        <x-term :term="$terms->firstWhere('translit', 'bētinžān')"/>
        <x-term :term="$terms->firstWhere('translit', 'bandōra')"/>
        <x-term :term="$terms->firstWhere('translit', 'filfil')"/>
        <x-term :term="$terms->firstWhere('translit', 'bāmye')"/>
        <x-term :term="$terms->firstWhere('translit', 'baṣal')"/>
        <x-term :term="$terms->firstWhere('translit', 'xyār')"/>
        <x-term :term="$terms->firstWhere('translit', 'fižl')"/>
        <x-term :term="$terms->firstWhere('translit', 'banžar')"/>
        <x-term :term="$terms->firstWhere('translit', 'žazar')"/>
        <x-term :term="$terms->firstWhere('translit', 'baṭāṭa')"/>
        <x-term :term="$terms->firstWhere('translit', 'zanžabīl')"/>
    </x-vocabulary>
    <x-vocabulary title="herbs">
        <x-term :term="$terms->firstWhere('translit', 'ʕušb')"/>
        <x-term :term="$terms->firstWhere('translit', 'rīħān')"/>
        <x-term :term="$terms->firstWhere('translit', 'naʕnaʕ')"/>
        <x-term :term="$terms->firstWhere('translit', 'maramiyye')"/>
        <x-term :term="$terms->firstWhere('translit', 'zaʕtar')"/>
        <x-term :term="$terms->firstWhere('translit', 'summāʔ')"/>
    </x-vocabulary>
    <x-vocabulary title="legumes">
        <x-term :term="$terms->firstWhere('translit', 'fūl')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʕadas')"/>
        <x-term :term="$terms->firstWhere('translit', 'ħummuṣ')"/>
        <x-term :term="$terms->firstWhere('translit', 'fāṣūlya')"/>
        <x-term :term="$terms->firstWhere('translit', 'bāzilla')"/>
    </x-vocabulary>
</div>
