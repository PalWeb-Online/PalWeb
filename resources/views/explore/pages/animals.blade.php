<x-page-head title="{{ __('animals') }}" blurb="Get to know the animal kingdom in Arabic, from the birds & the bees to familiar pets, farm
    animals & exotic creatures!">
    <x-link :href="route('explore.index')">{{ __('explore') }}</x-link>
    <x-link :href="route('explore.show', 'animals')">{{ __('animals') }}</x-link>
</x-page-head>

<div class="doc-section">
    <x-vocabulary title="mammals">
        <x-term :term="$terms->firstWhere('translit', 'ħaywān')"/>
        <x-term :term="$terms->firstWhere('translit', 'kalb')"/>
        <x-term :term="$terms->firstWhere('translit', 'taʕlab')"/>
        <x-term :term="$terms->firstWhere('translit', 'dīb')"/>
        <x-term :term="$terms->firstWhere('translit', 'bisse')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔasad')"/>
        <x-term :term="$terms->firstWhere('translit', 'nimr')"/>
        <x-term :term="$terms->firstWhere('translit', 'baʔar')"/>
        <x-term :term="$terms->firstWhere('translit', 'tōr')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʕižl')"/>
        <x-term :term="$terms->firstWhere('translit', 'ġanam')"/>
        <x-term :term="$terms->firstWhere('translit', 'xarūf')"/>
        <x-term :term="$terms->firstWhere('translit', 'māʕiz')"/>
        <x-term :term="$terms->firstWhere('translit', 'ħmār')"/>
        <x-term :term="$terms->firstWhere('translit', 'ħṣān')"/>
        <x-term :term="$terms->firstWhere('translit', 'faras')"/>
        <x-term :term="$terms->firstWhere('translit', 'baġl')"/>
        <x-term :term="$terms->firstWhere('translit', 'xanzīr')"/>
        <x-term :term="$terms->firstWhere('translit', 'dubb')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔird')"/>
        <x-term :term="$terms->firstWhere('translit', 'fīl')"/>
        <x-term :term="$terms->firstWhere('translit', 'fār')"/>
        <x-term :term="$terms->firstWhere('translit', 'sinžāb')"/>
        <x-term :term="$terms->firstWhere('translit', 'danab')"/>
    </x-vocabulary>
    <x-vocabulary title="other">
        <x-term :term="$terms->firstWhere('translit', 'samak')"/>
        <x-term :term="$terms->firstWhere('translit', 'ħūt')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔirš')"/>
        <x-term :term="$terms->firstWhere('translit', 'ṣadaf')"/>
        <x-term :term="$terms->firstWhere('translit', 'gambari')"/>
        <x-term :term="$terms->firstWhere('translit', 'ḍufdaʕ')"/>
        <x-term :term="$terms->firstWhere('translit', 'sulħafa')"/>
        <x-term :term="$terms->firstWhere('translit', 'timsāħ')"/>
        <x-term :term="$terms->firstWhere('translit', 'saħliyye')"/>
        <x-term :term="$terms->firstWhere('translit', 'ħayye')"/>
    </x-vocabulary>
    <x-vocabulary title="birds">
        <x-term :term="$terms->firstWhere('translit', 'ʕaṣfūr')"/>
        <x-term :term="$terms->firstWhere('translit', 'ħamām')"/>
        <x-term :term="$terms->firstWhere('translit', 'baṭṭ')"/>
        <x-term :term="$terms->firstWhere('translit', 'žāž')"/>
        <x-term :term="$terms->firstWhere('translit', 'dīk')"/>
        <x-term :term="$terms->firstWhere('translit', 'dīk ħabaš')"/>
        <x-term :term="$terms->firstWhere('translit', 'būm')"/>
        <x-term :term="$terms->firstWhere('translit', 'nisr')"/>
        <x-term :term="$terms->firstWhere('translit', 'ṭāwūs')"/>
        <x-term :term="$terms->firstWhere('translit', 'babaġāʔ')"/>
        <x-term :term="$terms->firstWhere('translit', 'baṭrīq')"/>
        <x-term :term="$terms->firstWhere('translit', 'žnāħ')"/>
        <x-term :term="$terms->firstWhere('translit', 'rīš')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʕušš')"/>
        <x-term :term="$terms->firstWhere('translit', 'ṭār')"/>
        <x-term :term="$terms->firstWhere('translit', 'bēḍ')"/>
        <x-term :term="$terms->firstWhere('translit', 'bāḍ')"/>
    </x-vocabulary>
    <x-vocabulary title="bugs">
        <x-term :term="$terms->firstWhere('translit', 'ħašara')"/>
        <x-term :term="$terms->firstWhere('translit', 'namūs')"/>
        <x-term :term="$terms->firstWhere('translit', 'dubbān')"/>
        <x-term :term="$terms->firstWhere('translit', 'naml')"/>
        <x-term :term="$terms->firstWhere('translit', 'naħl')"/>
        <x-term :term="$terms->firstWhere('translit', 'farāš')"/>
        <x-term :term="$terms->firstWhere('translit', 'žarād')"/>
        <x-term :term="$terms->firstWhere('translit', 'dūd')"/>
        <x-term :term="$terms->firstWhere('translit', 'ħalazōn')"/>
        <x-term :term="$terms->firstWhere('translit', 'xunfusa')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʕankabūt')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʕaqrab')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔaraṣ')"/>
    </x-vocabulary>
</div>
