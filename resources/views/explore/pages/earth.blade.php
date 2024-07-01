<x-page-head title="{{ __('earth') }}" blurb="Learn about the ecosystems & natural features that make life beautiful on this pale blue dot —
    then reach for the stars.">
    <x-link :href="route('explore.index')">{{ __('explore') }}</x-link>
    <x-link :href="route('explore.show', 'earth')">{{ __('earth') }}</x-link>
</x-page-head>

<div class="doc-section">
    <x-vocabulary title="nature">
        <x-term :term="$terms->firstWhere('translit', 'ṭabīʕa')"/>
        <x-term :term="$terms->firstWhere('translit', 'bīʔa')"/>
        <x-term :term="$terms->firstWhere('translit', 'nahr')"/>
        <x-term :term="$terms->firstWhere('translit', 'baħar')"/>
        <x-term :term="$terms->firstWhere('translit', 'muħīṭ')"/>
        <x-term :term="$terms->firstWhere('translit', 'šaṭṭ')"/>
        <x-term :term="$terms->firstWhere('translit', 'raml')"/>
        <x-term :term="$terms->firstWhere('translit', 'ṣaħra')"/>
        <x-term :term="$terms->firstWhere('translit', 'ġābe')"/>
        <x-term :term="$terms->firstWhere('translit', 'žabal')"/>
        <x-term :term="$terms->firstWhere('translit', 'ṣaxr')"/>
        <x-term :term="$terms->firstWhere('translit', 'ħažar')"/>
        <x-term :term="$terms->firstWhere('translit', 'ṭīn')"/>
    </x-vocabulary>
    <x-vocabulary title="climate">
        <x-term :term="$terms->firstWhere('translit', 'ṭaʔs')"/>
        <x-term :term="$terms->firstWhere('translit', 'žaww')"/>
        <x-term :term="$terms->firstWhere('translit', 'hawa')"/>
        <x-term :term="$terms->firstWhere('translit', 'ġēm')"/>
        <x-term :term="$terms->firstWhere('translit', 'mawsim')"/>
        <x-term :term="$terms->firstWhere('translit', 'rabīʕ')"/>
        <x-term :term="$terms->firstWhere('translit', 'ṣēf')"/>
        <x-term :term="$terms->firstWhere('translit', 'xarīf')"/>
        <x-term :term="$terms->firstWhere('translit', 'šita')"/>
        <x-term :term="$terms->firstWhere('translit', 'talž')"/>
        <x-term :term="$terms->firstWhere('translit', 'ruṭūbe')"/>
        <x-term :term="$terms->firstWhere('translit', 'daražt l-ħarāra')"/>
        <x-term :term="$terms->firstWhere('translit', 'zōbaʕa')"/>
        <x-term :term="$terms->firstWhere('translit', 'zilzāl')"/>
    </x-vocabulary>
    <x-vocabulary title="celestial bodies">
        <x-term :term="$terms->firstWhere('translit', 'nižme')"/>
        <x-term :term="$terms->firstWhere('translit', 'šams')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔamar')"/>
        <x-term :term="$terms->firstWhere('translit', 'kawkab')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʕuṭārid')"/>
        <x-term :term="$terms->firstWhere('translit', 'zuhra')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔarḍ')"/>
        <x-term :term="$terms->firstWhere('translit', 'marrīx')"/>
        <x-term :term="$terms->firstWhere('translit', 'muštari')"/>
        <x-term :term="$terms->firstWhere('translit', 'zuħal')"/>
    </x-vocabulary>
</div>
