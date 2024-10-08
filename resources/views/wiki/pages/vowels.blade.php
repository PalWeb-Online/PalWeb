<x-page-head title="{{ __('vowels') }}"
             blurb="Get to know the vowel inventory of Palestinian Arabic & all its varieties.">
    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>
    <x-link :href="route('wiki.show', 'phonology')">{{ __('phonology') }}</x-link>
    <x-link :href="route('wiki.show', 'vowels')">{{ __('vowels') }}</x-link>
</x-page-head>

<div class="doc-section">
    <h1>Inventory</h1>

    <p>The <b>vowel inventory</b> of Palestinian Arabic — that is, the set of all possible vowels in the language —
        differs somewhat from that of Standard Arabic, adding a few more vowels. Let's start by discussing the vowel
        inventory of Standard Arabic and build that of Palestinian Arabic from there.</p>

    <h2>Standard Arabic</h2>

    <p>Standard Arabic has a relatively small <b>vowel inventory</b>. Phonemically, there are just three
        types of vowels, which may be either short or long. Although these have a one-to-one relationship to
        characters of the Arabic script, the diacritics used to represent short vowels are not usually written.</p>

    <x-vocabulary title="vowels">
        <x-term-item arb="ــَـ" eng="(short open-front) /a/"/>
        <x-term-item arb="ــِـ" eng="(short closed-front) /i/"/>
        <x-term-item arb="ــُـ" eng="(short closed-back) /u/"/>
        <x-term-item arb="ا" eng="(long open-front) /aː/"/>
        <x-term-item arb="ي" eng="(long closed-front) /iː/"/>
        <x-term-item arb="و" eng="(long closed-back) /uː/"/>
    </x-vocabulary>

    <p>Note that <b>/a/</b> may actually be represented in a variety of ways, most notably including <b>ى</b> &
        <b>ة</b>.</p>

    <p>Phonetically, the realization of these vowels is relatively stable, with one primary exception: When in the
        vicinity of a pharyngealized consonant, <b>/a/</b> & <b>/aː/</b> are realized as <b>[ɑ]</b> & <b>[ɑː]</b>,
        respectively. In fact, much of what makes native speakers perceive a pharyngealized consonant is the
        realization of surrounding vowels, rather than the articulation of the consonant itself. Keep in mind that
        this effect on the realization of <b>/a/</b> is irrespective of its orthographic representation, whether it
        be as <b>ى</b> or <b>ة</b>.</p>
    <p>We will return to this issue presently.</p>

    <h2>Palestinian Arabic</h2>
    <p>Palestinian Arabic expands this <b>vowel inventory</b>, but it also differs in its realization of the
        foregoing phonemes. Let's begin with the latter point.</p>

    <p>In Palestinian Arabic, <b>/aː/</b> ranges between <b>[aː ~ æː]</b>. While <b>[æː]</b> is widespread among
        urban dialects in Palestine, <b>[a:]</b> is especially common in Jordan. In Northern Palestine & especially
        in Lebanon, this phoneme may range between <b>[ɛː ~ eː]</b>. Note that this shift, called <b>imāla</b>, only
        affects <b>/aː/</b> — not <b>/a/</b>.</p>

    <x-vocabulary title="open-front">
        <x-term-item :term="$terms->firstWhere('translit', 'bardān')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħammām')"/>
    </x-vocabulary>

    <p>Pharyngealization continues to affect the realization of <b>/a/</b> & <b>/aː/</b> in Palestinian
        Arabic. Since pharyngealization may spread more freely throughout a word in Spoken Arabic, the
        pharyngealized consonant doesn't have to be immediately before or after the vowel to have an effect.</p>
    <x-vocabulary title="open-front">
        <x-term-item :term="$terms->firstWhere('translit', 'ʕaṭšān')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṭayyāra')"/>
    </x-vocabulary>

    {{--        <p>Now is a good time to mention that the consonant <b>/r/</b> is usually pharyngealized as well. Additionally,--}}
    {{--            the consonant <b>/q/</b> produces <b>[ɑ]</b> as well. However, as we saw in <b>Lesson 5</b>, this--}}
    {{--            pronunciation is borrowed from Standard Arabic, whereas none of the native pronunciations of this letter--}}
    {{--            have this effect.</p>--}}

    <p>Some exceptional terms have arbitrary pharyngealization, producing an unexpected <b>[ɑ]</b>. However, these
        extremely rare; they include — but are not limited to — terms with <b>الله (ʔallah)</b> in them.</p>

    <x-vocabulary title="open-front">
        <x-term-item :term="$terms->firstWhere('translit', 'ʔāh')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ya ʔallah')"/>
    </x-vocabulary>

    <h2>Mid-Front & Mid-Back</h2>
    <p>In addition to the Standard Arabic
        vowels,
        we
        have short & long mid-front (<b>/e/</b>), as well as short and long mid-back (<b>/o/</b>).</p>

    <x-vocabulary title="mid-front">
        <x-term-item :term="$terms->firstWhere('translit', 'madrase')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'zēt')"/>
    </x-vocabulary>
    <x-vocabulary title="mid-back">
        <x-term-item :term="$terms->firstWhere('translit', 'barḍo')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'fōʔ')"/>
    </x-vocabulary>

    <p>Since there's no unique way to write the short and long <b>/e/</b> and <b>/o/</b> vowels, short and long
        <b>/e/</b>
        is written as short and long <b>/i/</b>, while short and long <b>/o/</b> is written as short and long <b>/u/</b>.
    </p>

    <p>However, short and long <b>/e/</b> & <b>/o/</b> arise in very specific circumstances. While short <b>/e/</b> only
        exists as the pronunciation of <b>ta marbūṭa (ة)</b>, short <b>/o/</b> only exists as the <b>3M</b> personal
        suffix.
        In fact, speakers who pronounce <b>ta marbūṭa</b> as <b>/a/</b> have no short <b>/e/</b> vowel phoneme at all.
        Meanwhile, long <b>/e/</b> and <b>/o/</b> specifically appear as a way of "flattening" the Standard Arabic
        dipthongs
        <b>/ai/</b> & <b>/au/</b>. Similarly, <b>/e:/</b> can sometimes arise in place of a <b>ya hamza (ئ)</b>, as in
        <a
            href="https://www.wiktionary.org/wiki/حيط#South_Levantine_Arabic" target="_blank">حيط</a> <b>(ħēṭ)</b> & <a
            href="https://www.wiktionary.org/wiki/عيلة#South_Levantine_Arabic" target="_blank">عيلة</a> <b>(3ēle)</b>
        (see
        <b>Lesson X</b>).</p>

    <div class="flex flashcard">
        <div class="content">
            <div class="front">
                <div class="title">STANDARD</div>
                <div class="arb">بَ<span class="red">يْ</span>ت</div>
                <div class="eng">b<span class="red">ay</span>t</div>
            </div>
            <div class="back">
                <div class="title">DIALECT</div>
                <div class="arb">ب<span class="grn">ي</span>ت</div>
                <div class="eng">b<span class="grn">ē</span>t</div>
            </div>
        </div>
    </div>

    <div class="flex flashcard">
        <div class="content">
            <div class="front">
                <div class="title">STANDARD</div>
                <div class="arb">يَ<span class="red">وْ</span>م</div>
                <div class="eng">y<span class="red">aw</span>m</div>
            </div>
            <div class="back">
                <div class="title">DIALECT</div>
                <div class="arb">ي<span class="grn">و</span>م</div>
                <div class="eng">y<span class="grn">ō</span>m</div>
            </div>
        </div>
    </div>

    <p>In reality, <b>/e:/</b> & <b>/o:/</b> are not that common; they seem more common than they really are simply
        because
        they appear in these types of high frequency nouns:</p>

    <div class="flex">
        <div class="content" onclick="lēl.play()">
            <div class="arb">ل<span class="orn">ي</span>ل</div>
            <div class="eng">l<span class="orn">ē</span>l</div>
            <div class="gloss">night</div>
        </div>
        <div class="content" onclick="bēn.play()">
            <div class="arb">ب<span class="orn">ي</span>ن</div>
            <div class="eng">b<span class="orn">ē</span>n</div>
            <div class="gloss">between</div>
        </div>
        <div class="content" onclick="zēt.play()">
            <div class="arb">ز<span class="orn">ي</span>ت</div>
            <div class="eng">z<span class="orn">ē</span>t</div>
            <div class="gloss">oil</div>
        </div>
    </div>

    <div class="flex">
        <div class="content" onclick="yōm.play()">
            <div class="arb">ي<span class="blu">و</span>م</div>
            <div class="eng">y<span class="blu">ō</span>m</div>
            <div class="gloss">day</div>
        </div>
        <div class="content" onclick="fōʔ.play()">
            <div class="arb">ف<span class="blu">و</span>ق</div>
            <div class="eng">f<span class="blu">ō</span>2</div>
            <div class="gloss">above</div>
        </div>
        <div class="content" onclick="jōz.play()">
            <div class="arb">ج<span class="blu">و</span>ز</div>
            <div class="eng">j<span class="blu">ō</span>z</div>
            <div class="gloss">husband</div>
        </div>
    </div>

    <p>As
        for <b>ta marbūṭa</b>, it's generally pronounced as <b>/e/</b>.</p>

    <div class="flex">
        <div class="content" onclick="maʕna.play()">
            <div class="arb">معن<span class="grn">ى</span></div>
            <div class="eng">maʕn<span class="grn">a</span></div>
        </div>
        <div class="content" onclick="f1_ḥaka.play()">
            <div class="arb">حك<span class="grn">ى</span></div>
            <div class="eng">ħak<span class="grn">a</span></div>
        </div>
    </div>

    <div class="flex">
        <div class="content" onclick="wajbe.play()">
            <div class="arb">وجب<span class="orn">ة</span></div>
            <div class="eng">wajb<span class="orn">e</span></div>
        </div>
        <div class="content" onclick="jumle.play()">
            <div class="arb">جمل<span class="orn">ة</span></div>
            <div class="eng">juml<span class="orn">e</span></div>
        </div>
    </div>

    <p>It's not a coincidence that these words look very similar. In Arabic three-letter words, the middle letter
        usually has sukun, as in <b>حرْب (ḥarb "war")</b> or <b>قلْب (ʔalb "heart")</b>. If the middle letter of the
        root is <b>ي</b> or <b>و</b> then the result is automatically a diphthong — <b>/ay/</b> or <b>/aw/</b>,
        respectively — that is therefore "flattened" in the dialect. Hence, if you see a word that already has three
        regular consonants, it's pretty much guaranteed that any <b>ي</b> or <b>و</b> vowel will be pronounced
        normally,
        as <b>/i:/</b> or <b>/u:/</b>.</p>
    <p>For instance, notice that the <b>ي</b> or <b>و</b> in certain common word patterns like <b>فعيل (fʕīl)</b> &
        <b>مفعول
            (mafʕūl)</b> are always pronounced <b>/i:/</b> or <b>/u:/</b> because they are "true" long vowels rather
        than flattened dipthongs (KBIR, Z8IR, MNI7 // MAKTUB, MAFHUM, MABRUK).</p>
    <p>However, occasionally three-letter words do originally have long vowel sounds in Standard Arabic. In these
        cases,
        the original quality of the vowel is retained (DIN, RI7 // SU2, NUR).</p>

    <p>Outside of these specific cases, the only situation where <b>/e:/</b> may be encountered is in the <b>Irregular
            Past
            Stem</b> of verbs with <b>B-Type & C-Type Roots</b> (see <b>Lesson 17</b>), where it is attached to the end
        of
        the stem. In all other cases, though, <b>ي</b> always represents <b>/i/</b>.</p>

    <div class="flex">
        <div class="content" onclick="f1_maša.play()">
            <div class="arb">مشى</div>
            <div class="eng">maša</div>
            <div class="gloss">he walked</div>
        </div>
        <div class="content" onclick="f1_maša$mašēt.play()">
            <div class="arb">مش<span class="orn">ي</span>ت</div>
            <div class="eng">maš<span class="orn">ē</span>t</div>
            <div class="gloss">I walked</div>
        </div>
        <div class="content" onclick="f1_maša$btimši.play()">
            <div class="arb">بتمش<span class="yel">ي</span></div>
            <div class="eng">btimš<span class="yel">i</span></div>
            <div class="gloss">you.F walked</div>
        </div>
    </div>

    <div class="flex">
        <div class="content" onclick="f1_ḥabb.play()">
            <div class="arb">حبّ</div>
            <div class="eng">ħabb</div>
            <div class="gloss">he loved</div>
        </div>
        <div class="content" onclick="f1_ḥabb$ḥabbēt.play()">
            <div class="arb">حبّ<span class="orn">ي</span>ت</div>
            <div class="eng">ħabb<span class="orn">ē</span>t</div>
            <div class="gloss">I loved</div>
        </div>
        <div class="content" onclick="f1_ḥabb$bitḥibbi.play()">
            <div class="arb">بتحبّ<span class="yel">ي</span></div>
            <div class="eng">bitħibb<span class="yel">i</span></div>
            <div class="gloss">you.F loved</div>
        </div>
    </div>

    <p>As for <b>/o:/</b>, some speakers pronounce the <b>1S</b> & <b>3M</b> forms of the <b>Present Tense</b> of
        hamze-initial terms (namely, <a href="https://www.wiktionary.org/wiki/أكل#South_Levantine_Arabic"
                                        target="_blank">أكل</a>
        & <a href="https://www.wiktionary.org/wiki/أخد#South_Levantine_Arabic" target="_blank">أخد</a>) with <b>/o:/</b>
        —
        but even this isn't universal. Otherwise, <b>و</b> always represents <b>/u/</b>.</p>

    <div class="flex">
        <div class="content">
            <div class="arb">ب<span class="blu">و</span>كل</div>
            <div class="eng">b<span class="blu">ō</span>kil</div>
            <div class="gloss">he eats</div>
        </div>
        <div class="content">
            <div class="arb">ب<span class="blu">و</span>خد</div>
            <div class="eng">b<span class="blu">ō</span>xid</div>
            <div class="gloss">he gets</div>
        </div>
    </div>

    <div class="flex">
        <div class="content">
            <div class="arb">بش<span class="prp">و</span>ف</div>
            <div class="eng">biš<span class="prp">ū</span>f</div>
            <div class="gloss">he sees</div>
        </div>
        <div class="content">
            <div class="arb">كتب<span class="prp">و</span></div>
            <div class="eng">katab<span class="prp">u</span></div>
            <div class="gloss">they wrote</div>
        </div>
    </div>

    <h1>Realization</h1>

    <p>How do we know when and how to pronounce every vowel? After all, in writing there are still just three vowel
        letters
        & three short vowel marks. As we’re about to see, some of these vowels only occur in specific settings and their
        pronunciation is affected by other things going on in the word.</p>

    <p>Be aware that the <b>vowel inventory</b> we have created is a list of the vowel <b>phonemes</b> of Palestinian
        Arabic; this is not necessarily how they are pronounced in reality. For instance, the original <b>/i/</b> &
        <b>/u/</b> short vowels are generally not as tense in the dialect; they sound like <b>[ɪ]</b> & <b>[ʊ]</b>, but
        may
        be more tense in stressed syllables and less tense is unstressed syllables.</p>

    <div class="flex">
        <div class="content" onclick="wijh.play()">
            <div class="arb">وِجِه</div>
            <div class="eng">/w<span class="yel">i</span>ʒ<span class="yel">i</span>h/</div>
            <div class="gloss">[w<span class="yel">ɪ</span>ʒ<span class="yel">ɪ</span>h]</div>
        </div>
        <div class="content" onclick="šuḡl.play()">
            <div class="arb">شُغُل</div>
            <div class="eng">/ʃ<span class="prp">u</span>ɣ<span class="prp">u</span>l/</div>
            <div class="gloss">[ʃ<span class="prp">ʊ</span>ɣ<span class="prp">ʊ</span>l]</div>
        </div>
    </div>

    <p>Additionally, long vowels are frequently shortened as an outcome of alternations caused by word stress (see <b>Lesson
            8</b>). When this happens, the original quality of the vowel remains intact. This can create instances of
        short
        <b>[e]</b> and <b>[o]</b>, although they are “officially” <b>/e:/</b> and <b>/o:/</b>; long <b>/i:/</b> &
        <b>/u:/</b> shortened by this process will sound more tense than their short counterparts would otherwise be as
        well.</p>

    <div class="flex">
        <div class="content" onclick="f1_ḥaka$ḥakētilha.play()">
            <div class="arb">حكيتلها</div>
            <div class="eng">/ħak<span class="orn">e:</span>t<span class="yel">i</span>lha/</div>
            <div class="gloss">[ħak<span class="orn">e</span>t<span class="yel">ɪ</span>lha]</div>
        </div>
        <div class="content" onclick="mizān.play()">
            <div class="arb">ميزان</div>
            <div class="eng">/m<span class="yel">i:</span>zæ:n/</div>
            <div class="gloss">[m<span class="yel">i</span>zæ:n]</div>
        </div>
    </div>

    <p>Additionally, <b>ta marbūṭa</b> is pronounced as <b>[a]</b> around the following consonants that are pronounced
        at
        the back of the mouth and throat:</p>

    <div class="flex">
        <div class="content" onclick="nusḵa.play()">
            <div class="arb">نسخ<span class="grn">ة</span></div>
            <div class="eng">[nusx<span class="grn">a</span>]</div>
            <div class="gloss">/nusx<span class="orn">e</span>/</div>
        </div>
        <div class="content" onclick="luḡa.play()">
            <div class="arb">لغ<span class="grn">ة</span></div>
            <div class="eng">[lʊɣ<span class="grn">a</span>]</div>
            <div class="gloss">/luɣ<span class="orn">e</span>/</div>
        </div>
        <div class="content" onclick="jāmʕa.play()">
            <div class="arb">جامع<span class="grn">ة</span></div>
            <div class="eng">[jæ:mʕ<span class="grn">a</span>]</div>
            <div class="gloss">/ja:mʕ<span class="orn">e</span>/</div>
        </div>
    </div>

    <div class="flex">
        <div class="content" onclick="ṣarāḥa.play()">
            <div class="arb">صراح<span class="grn">ة</span></div>
            <div class="eng">[sˤɑrɑ:ħ<span class="grn">a</span>]</div>
            <div class="gloss">/sˤara:ħ<span class="orn">e</span>/</div>
        </div>
        <div class="content" onclick="jiha.play()">
            <div class="arb">جه<span class="grn">ة</span></div>
            <div class="eng">[ʒɪh<span class="grn">a</span>]</div>
            <div class="gloss">/ʒih<span class="orn">e</span>/</div>
        </div>
        <div class="content" onclick="šaʔʔa.play()">
            <div class="arb">شقّ<span class="grn">ة</span></div>
            <div class="eng">[šaʔʔ<span class="grn">a</span>]</div>
            <div class="gloss">/šaʔʔ<span class="orn">e</span>/</div>
        </div>
    </div>

    <h1>The Epenthetic Vowel</h1>
    <div class="line"></div>

    Remember as well that in the dialect the article AL & the pronouns ANTA / ANTI / ANTUM are raised to /i/ — as in
    IL-3ARABY, INTA / INTI / INTU.

    <p>And that’s everything you need to know about <b>Vowels</b> in Palestinian Arabic. The purpose of this lesson is
        not
        to strictly memorize these rules, but to keep these ideas in mind as you continue to learn the dialect; these
        are
        all very subtle differences that you're not going to consciously & consistently pull off just by memorizing the
        rules for them. It's also so that you can get a better idea of how a word in writing should be pronounced, even
        if
        you don’t already know the word. Understanding patterns in Arabic is crucial to building an instinct for the
        language. I encourage you to practice listening and carefully imitating the speech of native speakers and it
        will
        probably start to come naturally with time.</p>

</div>
