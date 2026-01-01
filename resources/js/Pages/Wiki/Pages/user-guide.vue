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
        <h1>Introduction</h1>
        <p><b>PalWeb</b> is divided into a few primary areas:</p>
        <ul>
            <li>the <b>Academy</b>, which provides a course for learning Palestinian Arabic & other pedagogical material
                oriented toward learners;
            </li>
            <li>the <b>Library</b>, a detailed reference for Palestinian Arabic;</li>
            <li>the <b>Community</b>, the hub for user-generated learning material created using PalWeb's tools;</li>
            <li>the <b>Wiki</b>, which provides information about PalWeb & Palestinian Arabic;</li>
            <li>the <b>Workbench</b>, a suite of database-powered Palestinian Arabic learning tools.</li>
        </ul>

        <p>The <b>User Guide</b> is a section of the <b>Wiki</b> that contains information regarding the various
            features
            available on <b>PalWeb</b>.</p>

        <h2>Contents</h2>
        <ul>
            <li>Features</li>
            <ul>
                <li><a href="#search-genie">Search Genie</a></li>
            </ul>

            <li>
                <Link :href="route('wiki.show', 'academy')">Academy</Link>
            </li>
            <ul>
                <li>
                    <Link :href="route('wiki.show', 'academy')">Dialogs</Link>
                </li>
            </ul>

            <li>
                <Link :href="route('wiki.show', 'dictionary')">Dictionary</Link>
            </li>
            <ul>
                <li>
                    <Link :href="route('wiki.show', 'dictionary') + '#conventions'">Conventions</Link>
                </li>
                <ul>
                    <li>Transcription</li>
                    <li>Orthography</li>
                    <li>Indexing</li>
                </ul>
            </ul>

            <li><Link :href="route('wiki.show', 'workbench')">Workbench</Link></li>
            <ul>
                <li><Link :href="route('wiki.show', 'workbench') + '#deck-master'">Deck Master</Link></li>
                <li><Link :href="route('wiki.show', 'workbench') + '#record-wizard'">Sound Booth</Link></li>
            </ul>

            <li><Link :href="route('wiki.show', 'contributing')">Contributing</Link></li>
        </ul>
    </div>

    <div class="wiki-content-block" id="search-genie">
        <h1>Search Genie</h1>
        <p>The <b>Search Genie</b> is your all-in-one search solution on PalWeb. It is accessible anywhere that you see
            a
            button prompt on the top-right corner of the page (you should see it on this page), or in the navigation
            menu on
            mobile devices.</p>
        <p>Click the button prompt or use the shortcut <b>Cmd/Ctrl+K</b> to open the <b>Search Genie</b>. You will see
            three
            tabs representing the three types of models you can search for: <b>Terms</b>, <b>Sentences</b> &
            <b>Decks</b>.
            Select the model type you'd like to search for & start typing to see results in real time.</p>

        <h2>Query Anatomy</h2>
        <p>The <b>Search Genie</b> works by matching the search query with different properties on the model type you've
            selected.</p>
        <ul>
            <li>the <b>Search Genie</b> will return any <b>Term</b> that:</li>
            <ul>
                <li>the search query matches any of its Spellings in Arabic.</li>
                <li>the search query matches the English transcription of any of its Pronunciations.</li>
                <li>the search query matches the Arabic spelling or English transcription of any of its Inflections.
                </li>
                <li>the search query matches any of its Glosses (i.e. definitions) in English.</li>
            </ul>
            <li>the <b>Search Genie</b> will return any <b>Sentence</b> that:</li>
            <ul>
                <li>contains a Term matching any of the above criteria.</li>
                <li>the search query matches the form of a Term in the Sentence, in Arabic or English.</li>
            </ul>
            <li>the <b>Search Genie</b> will return any <b>Decks</b> that:</li>
            <ul>
                <li>contains a Term matching any of the above criteria.</li>
                <li>the search query matches the Deck's name.</li>
            </ul>
        </ul>
        <p>Let's assume you're searching for the Term <b>كثير (ktīr "many")</b>. You can find it by searching for any
            the
            following:</p>
        <ul>
            <li>by Spelling (Arabic): <b>كثير</b> or <b>كتير</b></li>
            <li>by Pronunciation (English): <b>ktīr</b> or <b>kŧīr</b></li>
            <li>by Inflection (Arabic, English): <b>كثار</b> or <b>ktār</b></li>
            <li>by Gloss (English): <b>"many"</b> or <b>"very"</b>, etc.</li>
        </ul>
        <p>You may also apply filters in the <b>Terms</b> tab of the <b>Search Genie</b> to narrow down your results in
            real
            time. You may apply any combination of these five filters:</p>
        <ul>
            <li>the Category is the part of speech a Term belongs to (e.g. Noun).</li>
            <li>the Attribute is the grammatical feature a Term may have (e.g. Masculine).</li>
            <li>the Form is the verbal form of a Term or from which it is derived (e.g. Form 2).</li>
            <li>the Singular Pattern is the word pattern of a Term in its default or singular form (e.g. CvCC).</li>
            <li>the Plural Pattern is the word pattern of a Term in its plural form (e.g. CCūC).</li>
        </ul>
        <p>Selecting the <b>Sentences</b> or <b>Decks</b> tab will then display all of the corresponding models that
            contain
            matching Terms. Any filters you've applied for Terms will persist while searching for Sentence & Decks, so
            you
            can search for Decks with a specific Term in it, or get all Sentences with a pseudo-verb.</p>

        <h2>Contextual Behavior</h2>
        <p>The <b>Search Genie</b> is context-aware, meaning that the action taken on selecting one of the results will
            differ depending on the site context. (The <b>Search Genie</b> informs of you the action it will take by
            way of a tooltip.)</p>
        <p>By default, selecting a result will take you to its page in the Dictionary, Corpus or Deck Library. In the
            <b>Deck Master</b>, the <b>Search Genie</b> is used to add Terms to a Deck. In the <b>Sound Booth</b>, it
            is used to add all Terms in a given Deck to the Queue of items to record.</p>

        <h2>Limitations</h2>
        <p>Unfortunately, there are some limitations to the functionality of the <b>Search Genie</b> that are inherent
            to
            how the database is structured & the conventions established on PalWeb, namely:</p>
        <ul>
            <li>Searching by English transcription only returns exact matches (i.e. <b>ktir</b> will not match
                <b>ktīr</b>),
                so you would have to be precise — perhaps unreasonably so. See
                <Link :href="route('wiki.show', 'dictionary') + '#conventions'">Conventions</Link>
                for the site's
                transcription standard.
            </li>
            <li>Inflections are stored with only one canonical spelling & transcription, so a search for <b>كتار</b> or
                <b>kŧār</b> will fail.
            </li>
            <li>Searching for the conjugated forms of verbs will not work because verbal conjugations are not stored as
                Inflections; they must be queried by their lemma form (i.e. the third-person masculine past).
            </li>
        </ul>
        <p>Ironically, because every attestation of a Term in a Sentence is stored with an indication of its form within
            the
            Sentence, it is actually possible to find Sentences that contain a verb in a specific conjugated form.</p>
    </div>
</template>

