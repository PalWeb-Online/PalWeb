<x-page-head title="{{ __('food') }}" blurb="Whet your appetite for language! Explore tantalizing terms for food & drink, spicing up your
    culinary conversations.">
    <x-link :href="route('explore.index')">{{ __('explore') }}</x-link>
    <x-link :href="route('explore.show', 'food')">{{ __('food') }}</x-link>
</x-page-head>

<div class="doc-section">
    <x-vocabulary title="meat & dairy">
        <x-term :term="$terms->firstWhere('translit', 'laħm')"/>
        <x-term :term="$terms->firstWhere('translit', 'sfīne')"/>
        <x-term :term="$terms->firstWhere('translit', 'ħalīb')"/>
        <x-term :term="$terms->firstWhere('translit', 'žibne')"/>
        <x-term :term="$terms->firstWhere('translit', 'zibde')"/>
        <x-term :term="$terms->firstWhere('translit', 'laban')"/>
        <x-term :term="$terms->firstWhere('translit', 'labane')"/>
    </x-vocabulary>
    <x-vocabulary title="cooking">
        <x-term :term="$terms->firstWhere('translit', 'ʔakl')"/>
        <x-term :term="$terms->firstWhere('translit', 'fṭūr')"/>
        <x-term :term="$terms->firstWhere('translit', 'ġada')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʕaša')"/>
        <x-term :term="$terms->firstWhere('translit', 'ṭabxa')"/>
        <x-term :term="$terms->firstWhere('translit', 'ṭabax')"/>
        <x-term :term="$terms->firstWhere('translit', 'maʔli')"/>
        <x-term :term="$terms->firstWhere('translit', 'mašwi')"/>
        <x-term :term="$terms->firstWhere('translit', 'maħši')"/>
        <x-term :term="$terms->firstWhere('translit', 'mistwy')"/>
    </x-vocabulary>
    <x-vocabulary title="grains">
        <x-term :term="$terms->firstWhere('translit', 'ruzz')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔamħ')"/>
        <x-term :term="$terms->firstWhere('translit', 'ṭħīn')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʕažīn')"/>
        <x-term :term="$terms->firstWhere('translit', 'xubiz')"/>
        <x-term :term="$terms->firstWhere('translit', 'šūfān')"/>
    </x-vocabulary>
    <x-vocabulary title="nuts">
        <x-term :term="$terms->firstWhere('translit', 'lōz')"/>
        <x-term :term="$terms->firstWhere('translit', 'žōz')"/>
        <x-term :term="$terms->firstWhere('translit', 'bunduʔ')"/>
        <x-term :term="$terms->firstWhere('translit', 'fustuʔ')"/>
        <x-term :term="$terms->firstWhere('translit', 'fūl sūdāni')"/>
    </x-vocabulary>
    <x-vocabulary title="condiments">
        <x-term :term="$terms->firstWhere('translit', 'malħ')"/>
        <x-term :term="$terms->firstWhere('translit', 'filfil')"/>
        <x-term :term="$terms->firstWhere('translit', 'sukkar')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʕasal')"/>
        <x-term :term="$terms->firstWhere('translit', 'bhār')"/>
        <x-term :term="$terms->firstWhere('translit', 'tōm')"/>
        <x-term :term="$terms->firstWhere('translit', 'zēt')"/>
        <x-term :term="$terms->firstWhere('translit', 'xall')"/>
        <x-term :term="$terms->firstWhere('translit', 'mxallal')"/>
        <x-term :term="$terms->firstWhere('translit', 'fiṭr')"/>
    </x-vocabulary>
    <x-vocabulary title="beverages">
        <x-term :term="$terms->firstWhere('translit', 'mayy')"/>
        <x-term :term="$terms->firstWhere('translit', 'šāy')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔahwe')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʕaṣīr')"/>
        <x-term :term="$terms->firstWhere('translit', 'saħlab')"/>
        <x-term :term="$terms->firstWhere('translit', 'bīra')"/>
        <x-term :term="$terms->firstWhere('translit', 'nbīd')"/>
        <x-term :term="$terms->firstWhere('translit', 'mašrūb')"/>
    </x-vocabulary>
</div>
