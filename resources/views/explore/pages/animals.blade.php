<x-page-head title="{{ __('animals') }}" blurb="Get to know the animal kingdom in Arabic, from the birds & the bees to familiar pets, farm
    animals & exotic creatures!">
    <x-link :href="route('explore.index')">{{ __('explore') }}</x-link>
    <x-link :href="route('explore.show', 'animals')">{{ __('animals') }}</x-link>
</x-page-head>

<div class="wiki-container">
    <x-vocabulary title="mammals">
        <x-term-item :term="$terms->firstWhere('translit', 'ħaywān')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'kalb')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'taʕlab')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'dīb')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'bisse')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔasad')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'nimr')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'baʔar')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'tōr')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕižl')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ġanam')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'xarūf')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'māʕiz')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħmār')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħṣān')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'faras')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'baġl')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'xanzīr')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'dubb')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔird')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'fīl')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'fār')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'sinžāb')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'danab')"/>
    </x-vocabulary>
    <x-vocabulary title="other">
        <x-term-item :term="$terms->firstWhere('translit', 'samak')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħūt')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔirš')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṣadaf')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'gambari')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ḍufdaʕ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'sulħafa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'timsāħ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'saħliyye')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħayye')"/>
    </x-vocabulary>
    <x-vocabulary title="birds">
        <x-term-item :term="$terms->firstWhere('translit', 'ʕaṣfūr')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħamām')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'baṭṭ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'žāž')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'dīk')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'dīk ħabaš')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'būm')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'nisr')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṭāwūs')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'babaġāʔ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'baṭrīq')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'žnāħ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'rīš')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕušš')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṭār')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'bēḍ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'bāḍ')"/>
    </x-vocabulary>
    <x-vocabulary title="bugs">
        <x-term-item :term="$terms->firstWhere('translit', 'ħašara')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'namūs')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'dubbān')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'naml')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'naħl')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'farāš')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'žarād')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'dūd')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħalazōn')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'xunfusa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕankabūt')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕaqrab')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔaraṣ')"/>
    </x-vocabulary>
</div>
