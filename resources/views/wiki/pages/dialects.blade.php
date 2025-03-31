<x-page-head title="{{ __('dialects') }}"
             blurb="Get to know the various forms & regional varieties of Palestinian Arabic.">
    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>
    <x-link :href="route('wiki.show', 'dialects')">{{ __('dialects') }}</x-link>
</x-page-head>

<div class="wiki-container">
    <h1>Isoglosses</h1>
    <p>Dialects are distinguished on the basis of isoglosses. However, the distribution of different distinguishing
        features of dialects do not always overlap.</p>

    <h2>قال وقلنا</h2>
    <p>The primary dialectal isogloss has a name in Arabic that is pronounced in accordance with the speaker's dialect:
        <b>قال وقلنا</b>. This isogloss is the first basis for the distinction between <b>Urban</b> dialects,
        <b>Rural</b> dialects, <b>Bedouin</b> dialects & <b>Druze</b> dialects.</p>
    <ul>
        <li><b>Urban Palestinian: /ʔaːl/</b></li>
        <li><b>Rural Palestinian: /kaːl/</b></li>
        <li><b>Bedouin Palestinian: /gaːl/</b></li>
        <li><b>Druze Palestinian: /qaːl/</b></li>
    </ul>

    <p>Other isoglosses highlight a distinction between one variety versus the others.</p>

    <h3>Interdentals to Stops</h3>
    <ul>
        <li><b>Urban Palestinian: /timm/</b></li>
        <li><b>non-Urban Palestinian: /θimm/</b></li>
    </ul>

    <h3>De-Affrication of /dʒ/</h3>
    <ul>
        <li><b>Urban Palestinian: /ʒaːr/</b></li>
        <li><b>non-Urban Palestinian: /dʒaːr/</b></li>
    </ul>

    <h3>Affrication of /k/</h3>
    <ul>
        <li><b>Rural Palestinian: /tʃalb/</b></li>
        <li><b>non-Rural Palestinian: /kalb/</b></li>
    </ul>

    <h2>ة</h2>
    <p>The second primary isogloss is less sociolectal & more geographic, marking a transition from the southern
        varieties of Levantine Arabic to the northern ones; this is the pronounciation of <b>ة</b>.</p>
    <ul>
        <li><b>Central Rural Palestinian: /a/</b></li>
        <li><b>Central Urban Palestinian: /e/</b></li>
        <li><b>Northern Palestinian: /i/</b></li>
    </ul>

    <h2>حبّ</h2>
    <p>A third isogloss of unclear distribution is the quality of the close vowel (either /i/ or /u/) in certain CvCC
        terms. It is not clear to what extent the pronunciation of all such terms is linked — if at all — or whether
        this is feature linked to the pronunciation of other terms like باكل vs. بوكل.</p>
    <ul>
        <li><b>i-Dialects: /ħilm/ /ʕilbe/ /yħibb/</b></li>
        <li><b>u-Dialects: /ħulm/ /ʕulbe/ /yħubb/</b></li>
    </ul>
</div>

{{--<div class="doc-section">--}}
{{--    <p>At it-Tafsir, we use the term "Palestinian Arabic" as a shorthand. However, we recognize a--}}
{{--        number--}}
{{--        of specific, distinct sub-dialects, labeled as: <b>"Jerusalem & Amman"</b>, <b>"Haifa & the--}}
{{--            Galilee"</b>, <b>"General Rural"</b>, <b>"General Bedouin"</b>.</p>--}}
{{--    <p>This is a very deliberate choice. While "South Levantine Arabic" is politically delimited as--}}
{{--        being of Palestine & Jordan (misguidedly so, which is why we changed it), the scope of--}}
{{--        "Levantine Arabic" makes no reference to political geography; moving forward, we should--}}
{{--        avoid--}}
{{--        using terms like "Palestine" to label sub-dialects within Levantine Arabic, because in--}}
{{--        reality--}}
{{--        there are usually multiple significantly distinct sub-dialects within the political borders--}}
{{--        of--}}
{{--        any given Levantine country. In the case of the urban dialects, I recommend choosing a set--}}
{{--        of--}}
{{--        representative cities or regions, suggesting that the sub-dialect "radiates out" from this--}}
{{--        core--}}
{{--        area while interacting with other varieties, rather than existing in the same form uniformly--}}
{{--        across a territory that is actually very diverse. As for the southern regions, I suggest--}}
{{--        "Jerusalem & Amman" & "Haifa & the Galilee". However, non-urban dialects don't have such a--}}
{{--        center, so they will inevitably be to a certain extent abstractions of a general sociolect;--}}
{{--        I--}}
{{--        recommend "Southern Bedouin" & "Southern Rural" for now to encompass those varieties.</p>--}}
{{--</div>--}}
