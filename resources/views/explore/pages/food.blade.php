<x-page-head title="{{ __('food') }}" blurb="Whet your appetite for language! Explore tantalizing terms for food & drink, spicing up your
    culinary conversations.">
    <x-link :href="route('explore.index')">{{ __('explore') }}</x-link>
    <x-link :href="route('explore.show', 'food')">{{ __('food') }}</x-link>
</x-page-head>

<div class="doc-section">
    <x-vocabulary title="meat & dairy">
        <x-term-item :term="$terms->firstWhere('translit', 'laħm')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'sfīne')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħalīb')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'žibne')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'zibde')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'laban')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'labane')"/>
    </x-vocabulary>
    <x-vocabulary title="cooking">
        <x-term-item :term="$terms->firstWhere('translit', 'ʔakl')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'fṭūr')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ġada')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕaša')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṭabxa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṭabax')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'maʔli')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'mašwi')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'maħši')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'mistwy')"/>
    </x-vocabulary>
    <x-vocabulary title="grains">
        <x-term-item :term="$terms->firstWhere('translit', 'ruzz')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔamħ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṭħīn')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕažīn')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'xubiz')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'šūfān')"/>
    </x-vocabulary>
    <x-vocabulary title="nuts">
        <x-term-item :term="$terms->firstWhere('translit', 'lōz')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'žōz')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'bunduʔ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'fustuʔ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'fūl sūdāni')"/>
    </x-vocabulary>
    <x-vocabulary title="condiments">
        <x-term-item :term="$terms->firstWhere('translit', 'malħ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'filfil')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'sukkar')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕasal')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'bhār')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'tōm')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'zēt')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'xall')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'mxallal')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'fiṭr')"/>
    </x-vocabulary>
    <x-vocabulary title="beverages">
        <x-term-item :term="$terms->firstWhere('translit', 'mayy')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'šāy')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔahwe')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕaṣīr')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'saħlab')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'bīra')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'nbīd')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'mašrūb')"/>
    </x-vocabulary>
</div>
