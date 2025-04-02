<x-page-head title="{{ __('health') }}" blurb="Don't get tongue-tied at your next visit to the doctor. Learn essential medical terms to stay
    safe & healthy no matter where you are.">
    <x-link :href="route('explore.index')">{{ __('explore') }}</x-link>
    <x-link :href="route('explore.show', 'health')">{{ __('health') }}</x-link>
</x-page-head>

<div class="wiki-content-block">
    <x-vocabulary title="general">
        <x-term-item :term="$terms->firstWhere('translit', 'ṭibb')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṭibbi')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṣiħħa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'mrīḍ')"/>
    </x-vocabulary>
    <x-vocabulary title="at the hospital">
        <x-term-item :term="$terms->firstWhere('translit', 'ʕiyāde')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'marīḍ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'duktōr')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'žarrāħ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'mumarriḍ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'mustašfa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'taʔmīn')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṭawāriʔ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'kammāme')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕamaliyye')"/>
    </x-vocabulary>
    <x-vocabulary title="at the pharmacy">
        <x-term-item :term="$terms->firstWhere('translit', 'ṣaydaliyye')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṣaydali')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕilāž')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'waṣfe')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'dawa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħabb')"/>
    </x-vocabulary>
    <x-vocabulary title="at the laboratory">
        <x-term-item :term="$terms->firstWhere('translit', 'muxtabar')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'natīže')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'faħṣ')"/>
    </x-vocabulary>
    <x-vocabulary title="diseases & conditions">
        <x-term-item :term="$terms->firstWhere('translit', 'maraḍ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕadwa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'rašħ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'žalṭa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'saraṭān')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔaʕma')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔaṭraš')"/>
    </x-vocabulary>
    <x-vocabulary title="symptoms">
        <x-term-item :term="$terms->firstWhere('translit', 'taʕb')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'wažaʕ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'sxūne')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔishāl')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔiltihāb')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ġaŧayān')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'stafraġ')"/>
    </x-vocabulary>
    <x-vocabulary title="body parts">
        <x-term-item :term="$terms->firstWhere('translit', 'rās')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'sinn')">
            <x-term-item subterm arb="دكتور سنان" eng="dentist"/>
        </x-term-item>
        <x-term-item :term="$terms->firstWhere('translit', 'ḍahr')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔalb')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'miʕde')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔīd')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔižr')"/>
    </x-vocabulary>
    <x-vocabulary title="mental health">
        <x-term-item :term="$terms->firstWhere('translit', 'nafsi')">
            <x-term-item subterm arb="صحّة نفسيّة" eng="mental health"/>
            <x-term-item subterm arb="دكتور نفسيّ" eng="psychologist, psychiatrist"/>
        </x-term-item>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔalaʔ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'tawattur')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔiktiʔāb')"/>
    </x-vocabulary>
</div>

{{--    <figure>--}}
{{--        <img--}}
{{--            src="https://upload.wikimedia.org/wikipedia/commons/d/d0/Paxlovid_pills_close-up%2C_scattered.jpg">--}}
{{--        <figcaption>لازم تشرب حبّة كلّ ثمن ساعات</figcaption>--}}
{{--    </figure>--}}
{{--    <figure>--}}
{{--        <img--}}
{{--            src="https://upload.wikimedia.org/wikipedia/commons/c/c8/3410903_%D8%A2%D8%B2%D9%85%D8%A7%DB%8C%D8%B4%DA%AF%D8%A7%D9%87_%D8%AA%D8%AE%D8%B5%D8%B5%DB%8C_%D8%AA%D8%B4%D8%AE%DB%8C%D8%B5_%DA%A9%D9%88%D9%88%DB%8C%D8%AF_%DB%B1%DB%B9_%D8%AF%D8%B1_%D8%A7%D9%87%D9%88%D8%A7%D8%B2.jpg">--}}
{{--        <figcaption>شو كانت نتيجة فحص الكورونا؟</figcaption>--}}
{{--    </figure>--}}
{{--    <figure>--}}
{{--        <img--}}
{{--            src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Headache_touching_forehead.jpg">--}}
{{--        <figcaption>من مبارح صار عندي سخونة ووجع راس</figcaption>--}}
{{--    </figure>--}}
{{--    <figure>--}}
{{--        <img--}}
{{--            src="https://upload.wikimedia.org/wikipedia/commons/5/55/Francesca_Mastrantonio_2017_-11.jpg">--}}
{{--        <figcaption>هي دكتورة نفسيّة متخصّصة بالاكتئاب</figcaption>--}}
{{--    </figure>--}}
