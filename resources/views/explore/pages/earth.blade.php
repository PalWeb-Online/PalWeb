<x-page-head title="{{ __('earth') }}" blurb="Learn about the ecosystems & natural features that make life beautiful on this pale blue dot —
    then reach for the stars.">
    <x-link :href="route('explore.index')">{{ __('explore') }}</x-link>
    <x-link :href="route('explore.show', 'earth')">{{ __('earth') }}</x-link>
</x-page-head>

<div class="wiki-container">
    <x-vocabulary title="nature">
        <x-term-item :term="$terms->firstWhere('translit', 'ṭabīʕa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'bīʔa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'nahr')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'baħar')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'muħīṭ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'šaṭṭ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'raml')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṣaħra')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ġābe')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'žabal')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṣaxr')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħažar')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṭīn')"/>
    </x-vocabulary>
    <x-vocabulary title="climate">
        <x-term-item :term="$terms->firstWhere('translit', 'ṭaʔs')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'žaww')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'hawa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ġēm')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'mawsim')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'rabīʕ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṣēf')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'xarīf')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'šita')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'talž')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ruṭūbe')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'daražt l-ħarāra')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'zōbaʕa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'zilzāl')"/>
    </x-vocabulary>
    <x-vocabulary title="celestial bodies">
        <x-term-item :term="$terms->firstWhere('translit', 'nižme')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'šams')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔamar')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'kawkab')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕuṭārid')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'zuhra')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔarḍ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'marrīx')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'muštari')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'zuħal')"/>
    </x-vocabulary>
</div>
