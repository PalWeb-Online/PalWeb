<script setup>
import {route} from "ziggy-js";
import Wiki from "../Wiki.vue";
import Layout from "../../../Shared/Layout.vue";

defineOptions({
    layout: [Layout, Wiki],
})
</script>
<template>
<div class="wiki-content-block">
    <h1>Inventory</h1>
    <p>In this section, we're going to discuss <b>Consonants</b> in Palestinian Arabic.</p>

    <p>In theory, the <b>consonant inventory</b> of any Palestinian Arabic speaker includes — but is not limited
        to — all the consonants that exist in Standard Arabic; that’s because practically everyone has some
        command of Standard Arabic, in addition to natively speaking the dialect. However, that doesn’t mean
        that all the Standard Arabic consonants natively exist in Palestinian Arabic. Some only occur in formal
        terms that are essentially borrowed from Standard Arabic.</p>

    <p>In the video that accompanies this lesson, I give pronunciation tips for the more unfamiliar sounds of
        the Arabic consonant inventory. In this written version of the lesson, I’ll focus on describing the
        situations in which the different consonants appear.</p>

    <h2>Native Consonants</h2>

    <p>The Palestinian Arabic consonant inventory consists of 25 consonants, which generally have a one-to-one
        correspondence with a letter of the Arabic abjad. In order of appearance, they
        are:</p>

<!--    <x-vocabulary>-->
<!--        <x-term-item arb="ب" eng="/b/"/>-->
<!--        <x-term-item arb="ت" eng="/t/"/>-->
<!--        <x-term-item arb="ج" eng="/ʒ/"/>-->
<!--        <x-term-item arb="ح" eng="/ħ/"/>-->
<!--        <x-term-item arb="خ" eng="/x/"/>-->
<!--        <x-term-item arb="د" eng="/d/"/>-->
<!--        <x-term-item arb="ر" eng="/r/"/>-->
<!--        <x-term-item arb="ز" eng="/z/"/>-->
<!--        <x-term-item arb="س" eng="/s/"/>-->
<!--        <x-term-item arb="ش" eng="/ʃ/"/>-->
<!--        <x-term-item arb="ص" eng="/sˤ/"/>-->
<!--        <x-term-item arb="ض" eng="/dˤ/"/>-->
<!--        <x-term-item arb="ط" eng="/tˤ/"/>-->
<!--        <x-term-item arb="ظ" eng="/zˤ/"/>-->
<!--        <x-term-item arb="ع" eng="/ʕ/"/>-->
<!--        <x-term-item arb="غ" eng="/ɣ/"/>-->
<!--        <x-term-item arb="ف" eng="/f/"/>-->
<!--        <x-term-item arb="ل" eng="/l/"/>-->
<!--        <x-term-item arb="ك" eng="/k/"/>-->
<!--        <x-term-item arb="ن" eng="/n/"/>-->
<!--        <x-term-item arb="م" eng="/m/"/>-->
<!--        <x-term-item arb="هـ" eng="/h/"/>-->
<!--        <x-term-item arb="ء" eng="/ʔ/"/>-->
<!--        <x-term-item arb="و" eng="/w/"/>-->
<!--        <x-term-item arb="ي" eng="/j/"/>-->
<!--    </x-vocabulary>-->

    <h3>Points of Articulation</h3>

    <p>Some of these letters may seem hard to pronounce at first. One way to practice is to identify
        voiced-devoiced pairs — that is, consonants that are pronounced the same way, except that one is voiced
        and the other is devoiced. You should be able to alternate between the two consonants by just switching
        your voice on and off. If you can make both sounds without moving your mouth at all, you're doing it
        right.</p>

    <div class="flex">
        <table class="tight">
            <tr>
                <th colspan="2">CONSONANT PAIRS</th>
            </tr>
            <tr>
                <th class="yel">DEVOC</th>
                <th class="blu">VOICE</th>
            </tr>
            <tr>
                <td>ت</td>
                <td>د</td>
            </tr>
            <tr>
                <td>ط</td>
                <td>ض</td>
            </tr>
            <tr>
                <td>س</td>
                <td>ز</td>
            </tr>
            <tr>
                <td>ص</td>
                <td>ظ</td>
            </tr>
            <tr>
                <td>ح</td>
                <td>ع</td>
            </tr>
            <tr>
                <td>خ</td>
                <td>غ</td>
            </tr>
            <tr>
                <td>ش</td>
                <td>ج</td>
            </tr>
        </table>
    </div>

    <h3>Pharyngealization</h3>

    <p><b>Pharyngealization</b> is a type of secondary articulation that may be added to any otherwise normal
        sound by raising the back of your tongue to the soft palate. It’s something you do independently from
        the main articulation of a consonant, meaning that theoretically any sound can be <b>pharyngealized</b>;
        for instance, the consonant <b>/l/</b> is <b>pharyngealized</b> in English.</p>

    <p>In Palestinian Arabic, there are four <b>pharyngealized</b> (a.k.a. “dark” or “emphatic”) consonants:</p>

<!--    <x-vocabulary>-->
<!--        <x-term-item arb="ص" eng="/sˤ/"/>-->
<!--        <x-term-item arb="ض" eng="/dˤ/"/>-->
<!--        <x-term-item arb="ط" eng="/tˤ/"/>-->
<!--        <x-term-item arb="ظ" eng="/zˤ/"/>-->
<!--    </x-vocabulary>-->

    <p>When pronouncing these types of consonants, the front of the tongue produces the primary articulation
        while the back of the tongue is raised to the soft palate. When you say <b>/tˤ/</b>, the front of your
        tongue is basically in the same place as when you say <b>/t/</b>; the difference is that with
        <b>/tˤ/</b> the back of your tongue is raised up.</p>

    <p>Don’t underestimate how much of the work is actually being done by the vowels around the consonant. As
        I’ll discuss on the lesson on vowels, <b>pharyngealized</b> consonants affect the surrounding vowels,
        especially short <b>/a/</b> & long <b>/æ/</b> — which become short & long <b>/ɑ/</b>, respectively. Even
        if you can’t articulate the consonant correctly at all, if you use the right vowels the difference will
        be negligible.</p>

    <p>Keep in mind that consonants in a word aren't pronounced independently from each other; your tongue is
        constantly moving into many different positions to articulate all these sounds. In reality, when there's
        a <b>pharyngealized</b> consonant in a word, the letters around it become <b>pharyngealized</b> too. So,
        don't over-articulate every consonant individually, but rather approach syllables & words as unit.</p>

    <div class="flex">
        <div class="content" onclick="انبسط.play()">
            <div class="arb">انبسط</div>
            <div class="eng">/nbasatˤ/</div>
            <div class="gloss">[ɪnbɑsˤɑtˤ]</div>
        </div>
    </div>

    <h2>Non-Native Consonants</h2>

    <p>A few consonants from Standard Arabic don’t exist natively in the dialect, but are pronounced in formal
        terms. Even then, they may be altered.</p>

    <h3>Dental Fricatives</h3>

<!--    <x-vocabulary>-->
<!--        <x-term-item arb="ث" eng="/θ/"/>-->
<!--        <x-term-item arb="ذ" eng="/ð/"/>-->
<!--        <x-term-item arb="ظ" eng="/ðˤ/"/>-->
<!--    </x-vocabulary>-->

    <p>In Arabic, there are three of these so-called dental fricatives. In the urban dialects of Palestinian
        Arabic, dental fricatives are pronounced as either alveolar fricatives or as alveolar stops; rural
        dialects maintain the original dental fricatives.</p>

    <div class="flex">
        <table class="tight">
            <tr>
                <th></th>
                <th class="prp">DENT</th>
                <th class="orn">FRIC</th>
                <th class="grn">STOP</th>
            </tr>
            <tr>
                <td class="yel">DEVOC</td>
                <td>ث</td>
                <td>س</td>
                <td>ت</td>
            </tr>
            <tr>
                <td class="blu">VOICE</td>
                <td>ذ</td>
                <td>ز</td>
                <td>د</td>
            </tr>
            <tr>
                <td class="red">PHRYN</td>
                <td>ظ</td>
                <td>ظ</td>
                <td>ض</td>
            </tr>
        </table>
    </div>

    <p>With regard to borrowed terms, they're either pronounced the original way, or as fricatives. Natively,
        Palestinian Arabic realizes them only as stops.</p>

<!--    <x-vocabulary>-->
<!--        <x-term-item :term="$terms->firstWhere('translit', 'ŧaqāfe')"/>-->
<!--        <x-term-item :term="$terms->firstWhere('translit', 'tumm')"/>-->
<!--    </x-vocabulary>-->

<!--    <x-vocabulary>-->
<!--        <x-term-item :term="$terms->firstWhere('translit', 'ʔiza')"/>-->
<!--        <x-term-item :term="$terms->firstWhere('translit', 'dāʔ')"/>-->
<!--    </x-vocabulary>-->

<!--    <x-vocabulary>-->
<!--        <x-term-item :term="$terms->firstWhere('translit', 'muwaẓẓaf')"/>-->
<!--        <x-term-item :term="$terms->firstWhere('translit', 'naḍḍaf')"/>-->
<!--    </x-vocabulary>-->

    <p>Notice that there is no pharyngealized form of <b>ز</b> in Standard Arabic; the native form is an
        original new sound without its own designated letter.</p>

    <p>Unfortunately, there’s no precise way to determine whether a word is a borrowed or native term, since the
        criteria of “formality” is rather vague. However, you can always check <b>Wiktionary</b> to see how any
        word is commonly pronounced in Palestinian Arabic. It helps that, since the orthography of the dialect
        is generally phonetic, the consonant letters themselves are often replaced too; this happens primarily
        to native terms, meaning they are most commonly substituted by stops.</p>

    <div class="flex">
        <div class="content" onclick="تمّ.play()">
            <div class="arb"><span class="grn">ت</span>مّ</div>
            <div class="eng"><span class="grn">t</span>umm</div>
        </div>
        <div class="content" onclick="داق.play()">
            <div class="arb"><span class="grn">د</span>اق</div>
            <div class="eng"><span class="grn">d</span>ā2</div>
        </div>
        <div class="content" onclick="نضّف.play()">
            <div class="arb">ن<span class="grn">ضّ</span>ف</div>
            <div class="eng">na<span class="grn">ḍḍ</span>af</div>
        </div>
    </div>

    <h3>Qāf</h3>

<!--    <x-vocabulary>-->
<!--        <x-term-item arb="ق" eng="/q/"/>-->
<!--    </x-vocabulary>-->

    <p>As is the case with the dental fricatives, <b>/q/</b> is conserved in formal terms. Natively, though,
        Palestinian Arabic replaces it with one of a variety of other sounds. (The Druze community is the only
        group that maintains <b>/q/</b> in their native dialects.)</p>

    <div class="flex">
        <div class="content">
            <div class="arb">م<span class="mag">ق</span>الة</div>
            <div class="eng">ma<span class="mag">q</span>āle</div>
        </div>
        <div class="content" onclick="قارن.play()">
            <div class="arb"><span class="mag">ق</span>ارن</div>
            <div class="eng"><span class="mag">q</span>āran</div>
        </div>
    </div>

    <div class="flex">
        <div class="content">
            <div class="title">DRUZE</div>
            <div class="arb">ب<span class="mag">ق</span>ول</div>
            <div class="eng">bi<span class="mag">q</span>ūl</div>
        </div>
    </div>

    <p>Indeed, the way you pronounce <b>/q/</b> can strongly reflects your dialect and background. Notably,
        rural dialects pronounce <b>/q/</b> as <b>/k/</b>, while urban dialects realize it as a <b>/ʔ/</b>. In
        Jordan, it’s very common to pronounce it as <b>/g/</b>, but in Palestine this is more specifically
        associated with the Bedouin community.</p>

    <div class="flex">
        <div class="content" onclick="قالوقلنا.play()">
            <div class="title">URBAN</div>
            <div class="arb"><span class="mag">ق</span>ال و<span class="mag">ق</span>ُلنا</div>
            <div class="eng"><span class="mag">2</span>āl w <span class="mag">2</span>ulna</div>
        </div>
    </div>

    <div class="flex">
        <div class="content" onclick="كالوكلنا.play()">
            <div class="title">RURAL</div>
            <div class="arb"><span class="mag">ق</span>ال و<span class="mag">ق</span>ُلنا</div>
            <div class="eng"><span class="mag">k</span>āl w <span class="mag">k</span>ulna</div>
        </div>
    </div>

    <div class="flex">
        <div class="content">
            <div class="title">BEDOUIN</div>
            <div class="arb"><span class="mag">ق</span>ال و<span class="mag">ق</span>ُلنا</div>
            <div class="eng"><span class="mag">g</span>āl w <span class="mag">g</span>ulna</div>
        </div>
    </div>

    <h1>Rural Dialects</h1>
    <p>All of the above regarding the consonant inventory of Palestinian Arabic applies specifically to <b>urban
            dialects</b>, whereas the consonant inventory of <b>rural dialects</b> introduces a few distinct
        differences. As already mentioned, <b>rural dialects</b> preserve the dental fricatives, and realize <b>/q/</b>
        as <b>/k/</b>. As for the original <b>/k/</b> itself, it becomes <b>/tʃ/ (ch)</b>. Additionally, the
        pronunciation of <b>/ʒ/</b> is affricated (<b>/dʒ/</b>), as in Standard Arabic. Here’s a summary of the
        sound changes in <b>rural dialects</b>:</p>

<!--    <x-vocabulary>-->
<!--        <x-term-item arb="ث" eng="/θ/"/>-->
<!--        <x-term-item arb="ذ" eng="/ð/"/>-->
<!--        <x-term-item arb="ظ" eng="/ðˤ/"/>-->
<!--        <x-term-item arb="ق" eng="/k/"/>-->
<!--        <x-term-item arb="ك" eng="/tʃ/"/>-->
<!--        <x-term-item arb="ج" eng="/dʒ/"/>-->
<!--    </x-vocabulary>-->

    <p>And that's everything you need to know about <b>Consonants</b> in Palestinian Arabic!</p>
</div>
</template>

