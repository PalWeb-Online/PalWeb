<x-page-head title="{{ __('user manual') }}" blurb="Understand how to use the Dictionary to get the best results!">
    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>
    <x-link :href="route('wiki.show', 'dictionary')">{{ __('dictionary') }}</x-link>
</x-page-head>

<div class="doc-section">
    <h1>Background</h1>

    <p>Welcome to the <b>PalWeb Dictionary</b>. PalWeb's digital interactive Dictionary is the culmination of several
        years of preliminary & smaller-scale work.</p>
    <p>My first lexicography efforts were casual, as they were initially for myself. At first, I created the <b>Beginner's
            Lexicon of Palestinian Arabic</b> to serve as a quick reference tool for the dialect: a digital pocket
        dictionary in the form of an interactive spreadsheet. With time, this snowballed into the <b>AJP Wiktionary</b>
        project.</p>
    <p>When the project began in early January
        2021, <a
            href="https://wiktionary.org/" target="_blank">Wiktionary</a> had around 10 entries for <b>South Levantine
            Arabic (AJP)</b> — the name given by <a
            href="https://en.wikipedia.org/wiki/Ethnologue"
            target="_blank">Ethnologue</a> to the southern dialects of the Levantine Arabic continuum present mainly
        in Palestine & Jordan. Initially, the goal was to create <b>Wiktionary</b> entries for every
        verb in the <b>Lexicon</b> — around 700 terms at the time. As others joined the team & the goal of 700
        entries was met & exceeded, the project's scope — and the rigor & detail
        of its entries — grew significantly. By July 2022, <b>Wiktionary</b> had over 2,800 <b>South Levantine
            Arabic</b> terms & nearly as many pronunciation samples, with hundreds of example sentences & usages.
        If you'd like to check out
        the entries, you can simply go to <a href="https://wiktionary.org/" target="_blank">Wiktionary</a> & search for
        a word in Arabic. If it's used in Palestinian Arabic, there should be an entry for it.</p>
    <p>Not long after the <b>Learn Palestinian Arabic</b> site started seriously growing in scope, I discovered that I
        could create my own dictionary for Palestinian Arabic from scratch & host it here; that is the <b>PalWeb
            Dictionary</b> you can find here today.</p>

    <p>In early January 2021, I started <b>the AJP Wiktionary Project</b> to document the
        Palestinian
        dialects of Spoken Arabic. At that time, the Wikimedia site <a href="http://wiktionary.org/"
                                                                       target="_blank">Wiktionary</a>
        had around 10 entries for "South Levantine Arabic" (AJP), the name given by <a
            href="https://en.wikipedia.org/wiki/Ethnologue" target="_blank">Ethnologue</a> to the
        Spoken
        Arabic dialects within the Levantine Arabic continuum that exist mainly within Palestine &
        Jordan (see <a href="{{ route('wiki.show', 'ajp') }}">What is AJP?</a>).</p>
    <p>The Project's initial goal was to create entries on <b>Wiktionary</b> for every verb on the
        <b>Beginner's
            Lexicon of Palestinian Arabic</b> — around 700 terms at the time. As others joined the
        team
        and the goal of 700 entries was met & exceeded, the Project's scope — and the rigor & detail
        of
        its entries — grew significantly. As of July 2022, there are +2,800 terms & +2,600
        recordings for AJP on Wiktionary, with hundreds of example sentences & various other
        features as
        well.</p>
    <p>Although I am satisfied with the outcomes of this work, Wiktionary has some significant
        limitations. Since Wiktionary is used for all languages at the same time, there is no real
        "hub
        page" for South Levantine Arabic. Moreover, it's not possible to search for an English word
        to
        find its equivalent in the dialect, meaning the main use case for Wiktionary is to find
        further
        information about a known Spoken Arabic term (like pronunciation information & example
        sentences). Additionally, moderators specializing in Standard Arabic & tasked with upholding
        particular style standards awarded us little freedom to choose how to present information
        about
        Spoken Arabic.</p>
    <p>Consequently, I had wanted for a long time to migrate the dictionary to a private site that I
        could manage independently. As I was building this site, I realized that by using a database
        to
        store dictionary data I could solve many of the issues we faced on Wiktionary, including
        searchability. Dynamically pulling the data from a database also means the style of entries
        is
        inherently standardized, as there is only one page that controls the display of all entries;
        this is infinitely more efficient than individually writing the data on every Wiktionary
        page
        using the site's markup language.</p>
</div>

<div class="doc-section">
    <h1>Conventions</h1>
    <p>Because of the lack of standardization in Palestinian Arabic, a variety of conventions had to be developed to
        maintain consistency across entries in the Dictionary.</p>

    <h2>Orthography</h2>
    <p>Generally speaking, terms are spelled phonemically. However, since Urban Palestinian Arabic has lost a handful
        of distinct phonemes that other varieties preserve (e.g. /θ/), the etymological spelling of these phonemes is
        maintained (e.g. ـثـ as in كثير). In these cases, the phonemic spelling for Urban Palestinian Arabic (i.e. كتير)
        is usually provided as an alternative spelling.</p>
    <p>A variety of letter forms & diacritics are commonly omitted in casual writing. In this Dictionary, short vowels
        are not written, but other phonemic graphemes — namely <b>hamze</b>, <b>shadde</b> & the lexicalized <b>tanwīn
            fatħa</b> — are always written.</p>
    <p>Some final vowels may be written in a variety of ways in casual writing; these are standardized as follows:</p>
    <ul>
        <li>final /o/ is always ـه (e.g. برضه)</li>
        <li>a final /e ~ a/ that changes to /t/ in the construct form is always ـة (e.g. منيحة)</li>
        <li>a final /a/ that does not change to /t/ in the construct form is ـى (e.g. حليوى) or ـا (e.g. برّا)</li>
    </ul>

    <h2>Indexing</h2>
    <p>Generally speaking, only lemmas are indexed in the Dictionary, while inflections & the like are not.
        <b>Singulative</b>
        nouns (e.g. تفّاحة) are considered inflections of <b>Collective</b> nouns (i.e. تفّاح), so they are not listed
        separately. <b>Active Participles</b> are only listed separately if they have an idiomatic meaning or a usage
        that is not clearly past-perfect in meaning.</p>
    <p>Every term has a single canonical transcription by which it is indexed in the database; this is the transcription
        of the term's first listed pronunciation. By default, this is the term's pronunciation in Central Urban
        Palestinian Arabic (i.e. the urban Arabic dialect of Jerusalem).</p>

    <x-vocabulary>
        <x-term-item :term="$terms->firstWhere('translit', 'ktīr')"/>
    </x-vocabulary>

    <p>In accordance with the conventions of Arabic dictionaries, all verbs are indexed in the 3rd-person masculine form
        (e.g. راح “to be; (lit.) he went”). By extension, 3rd-person masculine pronouns are used to list terms that
        require a pronoun, including pseudo-verbs & phrasal verbs:</p>

    <x-vocabulary>
        <x-term-item :term="$terms->firstWhere('translit', 'rāħ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'biddo')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'dār bālo')"/>
    </x-vocabulary>

    <p>As for phrases that require two pronouns that refer to two different subjects, the 3rd-person masculine pronoun
        is used first, followed by the 3rd-person feminine pronoun; this is to indicate that the two subjects are
        distinct.</p>

    <x-vocabulary>
        <x-term-item :term="$terms->firstWhere('translit', 'حقّه عليها')"/>
    </x-vocabulary>
    {{--    “to be s.o.’s fault; (lit.) it was her fault re: sth. done to him; she was to blame”--}}

    <p>I divide Palestinian Arabic lemmas into three types according to the morphology of the <b>lemma form</b>:</p>
    <p>1. <b>Words</b> are terms that exist as single, independent units.</p>
    <p>2. <b>Clitics</b>
        <span class="chart-item clitic" style="border-radius:1.2rem">
            <span class="chart-title"
                  style="text-transform:uppercase; border-radius:0.8rem; text-align:center">
                clitic
            </span>
        </span>
        are lemmas that cannot exist individually, but rather are attached to another lemma.</p>
    <p>3. <b>Idioms</b>
        <span class="chart-item clitic" style="border-radius:1.2rem">
            <span class="chart-title"
                  style="text-transform:uppercase; border-radius:0.8rem; text-align:center">
                idiom
            </span>
        </span>
        are lemmas that exist individually, but are composed of two or more other lemmas; what
        qualifies these terms as lemmas is that they have a meaning that is greater than the sum of its parts, so that
        one cannot necessarily derive the term's meaning merely by knowing the meaning of its components.</p>

    <h2>Transcription</h2>
    <p>On this site, I use a specialized <b>transcription</b> scheme to represent Palestinian Arabic in the Latin
        script. It should be noted that the native script of Palestinian Arabic is the Arabic script; the Latin script
        is never meant to replace the Arabic script, as we only use it here with two specific functions: as an
        educational aid & for academic purposes.</p>
    <p>As a linguistic tool, a modified form of the Latin script is used for the <b>IPA (International Phonetic
            Alphabet)</b>, used here for phonemic & phonetic representations of terms in the Dictionary. Since I am
        merely following the IPA guidelines, you can refer to another website for a description of this scheme.</p>
    <p>However, I have created my own scheme to transcribe Palestinian Arabic on this site, which is used for
        educational purposes. Be advised that some creative liberties have been taken for the sake of intelligibility at
        the expense of linguistic specificity — the role of the otherwise esoteric IPA scheme.</p>

    <p>Let's start by listing the glyphs used for different sounds in the Palestinian Arabic consonant inventory —
        including those from borrowings. Note that I am not listing the corresponding letters in the Arabic script,
        because this transcription scheme is a representation of sound, not of orthography. More information on spelling
        conventions in Spoken Arabic & those used by this site are further down.</p>

    <div style="font-family: 'JetBrains Mono', monospace; display: grid; grid-template-columns: repeat(1, 1fr)">
        <div>/b/ <b>b</b></div>
        <div>/t/ <b>t</b></div>
        <div>/θ/ <b>ŧ</b></div>
        <div>/ʒ/ <b>ž</b></div>
        <div>/dʒ/ <b>j</b></div>
        <div>/ħ/ <b>ħ</b></div>
        <div>/x/ <b>x</b></div>
        <div>/d/ <b>d</b></div>
        <div>/ð/ <b>ð</b></div>
        <div>/r/ <b>r</b></div>
        <div>/z/ <b>z</b></div>
        <div>/s/ <b>s</b></div>
        <div>/ʃ/ <b>š</b></div>
        <div>/sˤ/ <b>ṣ</b></div>
        <div>/dˤ/ <b>ḍ</b></div>
        <div>/tˤ/ <b>ṭ</b></div>
        <div>/zˤ/ <b>ẓ</b></div>
        <div>/ðˤ/ <b>ż</b></div>
        <div>/ʕ/ <b>ʕ</b></div>
        <div>/ɣ/ <b>ġ</b></div>
        <div>/f/ <b>f</b></div>
        <div>/q/ <b>q</b></div>
        <div>/g/ <b>g</b></div>
        <div>/k/ <b>k</b></div>
        <div>/tʃ/ <b>č</b></div>
        <div>/l/ <b>l</b></div>
        <div>/m/ <b>m</b></div>
        <div>/n/ <b>n</b></div>
        <div>/h/ <b>h</b></div>
        <div>/ʔ/ <b>ʔ</b></div>
        <div>/j/ <b>y</b></div>
        <div>/w/ <b>w</b></div>
    </div>

    <p>Now let's consider vowels:</p>
    <div style="font-family: 'JetBrains Mono', monospace; display: grid; grid-template-columns: repeat(1, 1fr)">
        <div>/a/ [a] [α] <b>a</b></div>
        <div>/i/ [ɪ] [i]* <b>i</b></div>
        <div>/u/ [ʊ] [u]* <b>u</b></div>
        <div>/aː/ [æ(ː)] [ɑ(ː)] <b>ā (a)</b></div>
        <div>/iː/ [i(ː)] <b>ī (i)</b></div>
        <div>/uː/ [u(ː)] <b>ū (u)</b></div>
        <div>/eː/ [e(ː)] <b>ē (e)</b></div>
        <div>/oː/ [o(ː)] <b>ō (o)</b></div>
    </div>
    <p>Note that no short <b>e</b> or <b>o</b> is individually listed; that is because this Dictionary agrees with the
        theory that these are only present on the surface after a final long <b>ē</b> or <b>ō</b> — primarily <b>ة</b>
        or <b>ه</b>, respectively — is shortened. Still, shortened long vowels are always represented with short-vowel
        glyphs.</p>
    <p>Note that epenthetic vowels are never written, because they manifest differently for different speakers (e.g. <b>muškile</b>
        vs. <b>mušikle</b>) — if at all (e.g. <b>tħammam</b> vs. <b>itħammam</b>).</p>
    <p>In addition to representing sound, this transcription scheme shows word boundaries by linking clitics to other
        lemmas with a hyphen (e.g. <b>l-bēt</b>, <b>w-l-bēt</b>, <b>b-l-bēt</b>, <b>ha-l-bēt</b>, <b>b-ha-l-bēt</b>, <b>la-ha-l-bēt</b>).
    </p>
</div>
<div class="doc-section">
    <h1>Features</h1>

    <h2 id="search">Term Search & Filtering</h2>
    <p><b>Searching for Terms</b> works by finding matches between the search query & a handful of database attributes.
        Let's assume we
        are trying to find the word <b>كثير (ktīr "many")</b>. We can find it by searching for the following:</p>
    <ul>
        <li>its Arabic spelling, including alternative spellings (i.e. كثير or كتير)</li>
        <li>its transcribed pronunciation in any dialect (i.e. ktīr or kŧīr)</li>
        <li>its inflections, whether in Arabic or transliterated (e.g. كثار or ktār)</li>
        <li>its meanings in English (e.g. "many", "very", etc.)</li>
    </ul>
    <p>However, there are some limitations to the search function that are inherent to how the database is structured &
        the
        conventions that have been established:</p>
    <ul>
        <li>inflections are stored with only one canonical spelling & transcription, so a search for <b>كتار</b> or
            <b>kŧār</b> will fail
        </li>
    </ul>

    <p><b>Searching for Sentences</b> works similarly:</p>
    <ul>
        <li>Search for any Arabic term either in Arabic (e.g. يوكل) or transliterated (e.g. yōkil). Doing this will
            return all sentences — if there are any — where this term is used in this exact form. You have to be
            precise in your transliteration. Check the <b>User Manual</b> for the site's transliteration standard.
        </li>
        <li>Search for any Arabic word in its dictionary form transliterated (e.g. ʔakal). Doing this will return
            all sentences — if there are any — where this term is used in any of its forms. Although you must use
            the term's transliterated form, this is the only way to retrieve all forms of the term.
        </li>
    </ul>

    <p><b>Filtering Terms</b> may be done according to five attributes:</p>
    <ul>
        <li>Category: the part of speech a term belongs to (e.g. Noun).</li>
        <li>Subtype: a secondary category a term may optionally belong to (e.g. Masculine).</li>
        <li>Form: the verbal form of a term or from which it is derived (e.g. Form 2).</li>
        <li>Singular Pattern: the word pattern of a term in its default or singular form (e.g. CvCC).</li>
        <li>Plural Pattern: the word pattern of a term in its plural form (e.g. CCūC).</li>
    </ul>
</div>
<div class="doc-section">
    <h1>Entry Content</h1>
    <p>Creating a dictionary is more than just listing terms & writing their definitions. Indeed,
        any
        given term is far more than just its definition: it abides by rules governing its use, it
        has
        inflection types & conjugations, semantically related terms & more. In this section, I list
        all
        the data categories presented in <b>the AJP Dictionary</b> & the types of values they may
        contain.</p>

    <h2>Root</h2>
    <div class="data-box" style="width: 30%"
         x-data="{ open: false }">
        <div class="data-box-title">
            <h2>{{ __('root') }}</h2>
            <button @click="open = !open" x-text="open ? 'expand_more' : 'expand_less'"
                    class="toggle-button material-symbols-rounded">
            </button>
        </div>
        <div class="data-box-contents">
            <div class="headword">
                <div style="margin: 0 0.8rem 0 2.4rem">
                    <span>ب ر د</span>
                    <span
                        style="font-weight:400; font-size: 1.6rem">(b r d)</span>
                </div>
            </div>
            <div x-show="open">
                <div style="display: flex; flex-flow: column; gap: 0.4rem">
                    <div class="inline-chart">
                        <a href="/dictionary/noun/bard" target="_blank"
                           class="chart-item">
                            <div class="chart-title">برد</div>
                            <div>cold
                            </div>
                        </a>
                    </div>
                    <div class="inline-chart">
                        <a href="/dictionary/noun/barad" target="_blank"
                           class="chart-item">
                            <div class="chart-title">برد</div>
                            <div>hail
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <p>The <b>Root</b> box displays the term's root, if it has one. (<a href="{{ route('wiki.show', 'roots') }}">What is
            a root?</a>) If more than one term has a given root, you can click on the arrow to show all terms that share
        it. Try it with the example above!</p>
    <p>Roots are assigned somewhat more loosely here than they might be in other sources, to keep
        etymologically related terms together. Roots are determined after removing affixes & accounting for certain
        phenomena like reduplication. Hence, the root of <b>كندرجيّ (kundarži)</b> is listed as <b>ك ن د ر (k n d r)</b>
        & the root of <b>نطنط (naṭnaṭ)</b> is listed as <b>ن ط ط (n ṭ ṭ)</b>.
    </p>

    <h2>Pronunciation</h2>
    <div class="data-box" style="width: 70%">
        <div class="inline-chart pronunciation">
            <div
                style="display:flex; align-items: center; flex-flow: row wrap; padding: 0.4rem; gap: 0.4rem">
                <a class="chart-item"
                   onclick="ktīr.play()">
                    <div class="chart-title">/ktiːr/</div>
                    <div>[ktiːr]</div>
                </a>
                <div style="margin: 0 0.4rem">
                    -> Urban Palestinian
                </div>
                <script type="text/javascript">
                    var ktīr = new Howl({src: ['https://abdulbaha.fra1.cdn.digitaloceanspaces.com/audio/ktīr.mp3']});
                </script>
            </div>
            <div
                style="display:flex; align-items: center; flex-flow: row wrap; padding: 0.4rem; gap: 0.4rem">
                <a class="chart-item borrowed"
                   onclick="maqāle.play()">
                    <div class="chart-title">/maqaːle/</div>
                    <div>[maˈqaː.le]</div>
                </a>
                <div style="margin: 0 0.4rem">
                    -> General Palestinian
                </div>
                <script type="text/javascript">
                    var maqāle = new Howl({src: ['https://abdulbaha.fra1.cdn.digitaloceanspaces.com/audio/maqāle.mp3']});
                </script>
            </div>
        </div>
    </div>


    <p>The <b>Pronunciation</b> box displays three attributes for every pronunciation of a term: Firstly, its phonemic
        representation & its narrow phonetic transcription, written in the <b>International Phonetic Alphabet (IPA)</b>.
        Click on these IPA transcriptions to hear the term pronounced out loud.</p>
    <p>Secondly, the dialect associated with the pronunciation in question is shown. (<a
            href="{{ route('wiki.show', 'dialects') }}">What are the dialects of Palestinian Arabic?</a>) The first
        pronunciation listed is always the term's canonical pronunciation. By default, this is the <b>General
            Palestinian</b> pronunciation, but in cases of dialectal variation the <b>Central Urban Palestinian</b>
        dialect is used. (See <b>Indexing</b>.)</p>
    <p>Every pronunciation also has an associated transcription that is not shown, but the transcription of the term's
        canonical pronunciation is the term's canonical transcription, which is shown in the headword & is the basis of
        the term's URL path.</p>
    <p>Unique phonemic representations are provided on separate lines, while unique
        phonetic realizations of a single phonemic representation are written on the same line.</p>

    <h2>Variants</h2>

    <p>Some terms have <b>variants</b>. By default, a term is considered to be the <b>primary form</b> of itself; these
        are <b class="grn">primary terms</b>. Conversely, <b>variants</b> (i.e. <b>alternative forms</b>) of terms are
        <b class="yel">alternative terms</b>. Whether a term is a primary or a secondary term has a minor effect on
        where & how it appears in the dictionary.</p>
    <p>Whether a term is considered the canonical version or an alternative form is somewhat arbitrary, but is
        influenced by two factors: firstly, more common forms are considered primary over less common forms, like
        so:</p>

    <p style="font-family: 'JetBrains Mono', monospace"><b class="grn">farja</b> & <b class="yel">warra</b></p>

    <p>Secondly, inherited forms are considered primary over borrowed forms. However, one should not confuse true <b>doublets</b>
        for pairs of inherited & borrowed terms that are synonymous.</p>

    <p style="font-family: 'JetBrains Mono', monospace"><b class="grn">(inh.) bil-ʕāde</b> & <b class="yel">(bor.)
            ʕādatan</b></p>
    <p style="font-family: 'JetBrains Mono', monospace"><b class="grn">(inh.) bil-marra</b> & <b class="grn">(bor.)
            ʔabadan</b></p>

    <h2>Glosses</h2>

    <h2>Abbreviations</h2>
    <ul>
        <li>aux. — auxiliary</li>
        <li>by ext. — by extension</li>
        <li>by gen. — by generalization</li>
        <li>e.g. — for example, for instance</li>
        <li>end. — endearing</li>
        <li>esp. — especially</li>
        <li>fig. — figurative</li>
        <li>frm. — formal, polite, respectful</li>
        <li>i.e. — that is; in other words</li>
        <li>inc. — including (but not limited to)</li>
        <li>lit. — literally</li>
        <li>of — in reference to (e.g. "of person")</li>
        <li>pl. — when in the plural</li>
        <li>r.t. — of or relating to (e.g. "r.t. military")</li>
        <li>re: — regarding</li>
        <li>s.o. / s.o.’s — someone / someone’s</li>
        <li>sing. — when in the singulative</li>
        <li>sth. — something</li>
        <li>tech. — technical</li>
        <li>vlg. — vulgar</li>
        <li>vs. — rather than, as opposed to, in contrast with</li>
        <li>f.b. — followed by?
            <ul>
                <li>see شو (could be: (شو مـ) whatever)</li>
            </ul>
        </li>
    </ul>

    <h2>Relatives</h2>
    <p>Rather than <b>terms</b> having synonyms & antonyms, <b>glosses</b> do. Namely, <b>glosses</b> have <b>gloss
            relatives</b>, which are <b>synonyms</b>, <b>antonyms</b> & — in the case of verbs — <b>valence pairs</b>.
    </p>
    <p>Gloss relatives only include <b class="grn">primary terms</b>, not <b class="yel">alternative terms</b>, like so:
    </p>
    <p style="font-family: 'JetBrains Mono', monospace"><b class="grn">yimkin</b> -> <b class="grn">bijūz</b> & <b
            class="grn">balki</b> (-<b class="yel">barki</b>)</p>
    <p>However, <b class="yel">alternative terms</b> do list their synonyms:</p>
    <p style="font-family: 'JetBrains Mono', monospace"><b class="grn">balki</b> -> <b class="grn">bijūz</b> & <b
            class="grn">yimkin</b></p>
    <p style="font-family: 'JetBrains Mono', monospace"><b class="yel">barki</b> -> <b class="grn">bijūz</b> & <b
            class="grn">yimkin</b></p>
</div>

<div class="doc-section">
    <h1>Glossary</h1>

    <x-vocabulary title="vowels">
        <x-term-item arb="ــَـ" eng="fatħa: /a/"/>
        <x-term-item arb="ــِـ" eng="kasra: /i/"/>
        <x-term-item arb="ــُـ" eng="ḍamme: /u/"/>
        <x-term-item arb="ا" eng="ʔalif: /aː/"/>
        <x-term-item arb="ي" eng="yā: /iː/ /j/"/>
        <x-term-item arb="و" eng="wāw: /uː/ /w/"/>
        <x-term-item arb="ى" eng="ʔalif maksūra: /a/"/>
        <x-term-item arb="ة" eng="tā marbūṭa: /i ~ e ~ a/"/>
    </x-vocabulary>

    <x-vocabulary title="persons">
        <x-term-item arb="أنا" eng="1S: first-person singular"/>
        <x-term-item arb="إحنا" eng="1P: first-person plural"/>
        <x-term-item arb="إنتا" eng="2M: second-person masculine singular"/>
        <x-term-item arb="إنتي" eng="2F: second-person feminine singular"/>
        <x-term-item arb="إنتو" eng="2P: second-person plural"/>
        <x-term-item arb="هو" eng="3M: third-person masculine singular"/>
        <x-term-item arb="هي" eng="3F: third-person feminine singular"/>
        <x-term-item arb="همّه" eng="3P: third-person plural"/>
    </x-vocabulary>
</div>
