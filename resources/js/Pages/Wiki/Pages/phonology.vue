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
        <h1>Overview</h1>
        <p>In this section of the Wiki, the phonological features of Palestinian Arabic will be discussed. These
            features are divided into four areas:</p>
        <ol>
            <li><b>Vowels</b>: the vowel inventory of Palestinian Arabic is discussed.</li>
            <li><b>Consonants</b>: the consonant inventory of Palestinian Arabic is discussed.</li>
            <li><b>Syllables & Stress</b>: the syllable types & stress assignment in Palestinian Arabic are discussed.
            </li>
            <li><b>Phonotactics</b>: the interactions of consonants, admissible clustering & vowel epenthesis are
                discussed.
            </li>
        </ol>

        <p>Palestinian Arabic is really a collection of dialects & varieties, so the information in these areas refers
            primarily to the so-called Central Urban Palestinian dialect, i.e. the urban dialect of Jerusalem. However,
            variation from the described norms is addressed as well.</p>
        <p>Some phonological features differ across dialect types & indeed these features are one basis for
            distinguishing
            between dialect groups. (<Link :href="route('wiki.show', 'dialects')">What are the dialects of Palestinian
                Arabic?</Link>) Additionally, due to diglossia with Standard Arabic, the phonological features of Standard
            Arabic coexist with those native to Palestinian Arabic. Hence, it is necessary that we distinguish between
            phonological features of Palestinian Arabic per se versus those of Standard Arabic, which may be encountered
            in a given term.</p>
        <p>In the Dictionary, this distinction is presented as an opposition between <b>Inherited</b> & <b>Borrowed</b>
            terms. <b>Inherited</b> terms display the phonological features of Palestinian Arabic in their
            pronunciation,
            while <b>Borrowed</b> terms display one or more features of Standard Arabic, including terms that display
            features of both varieties. (Terms introduced from other languages that also display non-native features are
            also listed as <b>Borrowed</b>.) Some terms do not feature any elements that would allow one to determine
            whether the term is one or the other; these are listed as <b>Inherited</b> by default.
        </p>
        <p>A list of phonological features characteristic of Standard Arabic that are not native to
            Palestinian Arabic but may be found in the lexicon is provided below. Keep in mind that some distinctions
            between Palestinian Arabic & Standard Arabic do not obtain for certain types of dialects.</p>
        <ul>
            <li>/i/ & /u/ in an open syllable where its removal would not be inadmissible</li>
            <ul>
                <li><b>Inherited: حكاية (ħkāye)</b></li>
                <li><b>Borrowed: خيانة (xiyāne)</b></li>
            </ul>
            <li>[i] & [u] in an open syllable (except before /j/ & /w/, respectively)</li>
            <ul>
                <li><b>Inherited: كتب [kʊtʊb]</b></li>
                <li><b>Borrowed: ممرّض [mumɑrrɪdˤ]</b></li>
            </ul>
            <li>non-Druze: the consonant /q/</li>
            <ul>
                <li><b>Inherited: قال (ʔāl)</b></li>
                <li><b>Borrowed: مقالة (maqāle)</b></li>
            </ul>
            <li>non-Rural: the consonants /θ/ /ð/ /ðˤ/; these may surface as [s] [z] [zˤ]</li>
            <ul>
                <li><b>Inherited: نظّارات or نضّارات (naḍḍārāt)</b></li>
                <li><b>Borrowed: منظر (manẓar)</b></li>
            </ul>
        </ul>
    </div>
</template>

