<x-page-head title="{{ __('roots') }}">
    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>
    <a>{{ __('morphology') }}</a>
    <x-link :href="route('wiki.show', 'roots')">{{ __('roots') }}</x-link>
</x-page-head>

<div class="wiki-container">
    <p>In Arabic, nearly every term has a triliteral (i.e. three-letter) Root; arguably, the
        majority of
        terms in Arabic are ultimately no more than a root placed in one of a few high-frequency
        word
        patterns. For instance, the root of <b>مدرسة</b> ("school") is <b>د ر س</b>. Note that roots
        are
        not lexical items: they are not terms with a defined meaning; they are simply what remains
        of a
        term once you remove the elements of the pattern. If you remove the root from <b>madrase</b>,
        you get a pattern often used for location nouns; if you remove the pattern, you get a root
        related to studies. Only together, they mean "school".
    <p>
        <img src="{{ asset('img/fig2.svg') }}" style="display: block; margin: 0 auto">
    <p>Some terms (e.g. <b>ترجمة</b>) have quadriliteral (i.e. four-letter) roots. Specific
        morphological (i.e. word-formation) patterns exist for quadriliteral roots, whereas none
        exist
        for terms with "roots" with anything other than 3/4 letters. Accordingly, I consider terms
        like
        <b>من (min)</b> as simply having no root. Terms with more than 4 letters that are not part
        of a
        particular morphological pattern (e.g. <b>تلفزيون</b>) are practically always loanwords that
        are
        not morphologically flexible. However, native morphological patterns are extended to
        loanwords
        from which 3/4 root consonants may be extracted.</p>
    <div style="display: flex; justify-content: flex-start">
        <div class="inline-chart">
            <div>NOUN تلفون (tilfōn, "telephone") -> ROOT تلفن (tlfn) -> VERB تلفن (talfan, "to
                call")
            </div>
        </div>
    </div>
    <p>Accordingly, not all terms in the dictionary have a <b>Root</b> section. However, for those
        that
        do include it, the section indicates the term's root alongside a list of all other terms
        with
        the same root.</p>
</div>
