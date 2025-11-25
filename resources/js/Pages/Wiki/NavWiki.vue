<script setup>
import {route} from "ziggy-js";

const props = defineProps({
    page: String,
})
</script>
<template>
    <div class="nav-wiki">
        <Link :href="route('wiki.show', 'faq')" :class="{ active: page === 'faq' }">FAQ</Link>
        <Link :href="route('wiki.show', 'about')" :class="{ active: page === 'about' }">About</Link>
        <Link :href="route('wiki.show', 'release-notes')" :class="{ active: page === 'release-notes' }">Release Notes</Link>

        <div class="nav-wiki-section">
            <Link :href="route('wiki.show', 'user-guide')" :class="{ active: page === 'user-guide' }">User Guide</Link>
            <Link :href="route('wiki.show', 'academy')" :class="{ active: page === 'academy' }">Academy</Link>
            <Link :href="route('wiki.show', 'dictionary')" :class="{ active: page === 'dictionary' }">Dictionary</Link>
            <Link :href="route('wiki.show', 'workbench')" :class="{ active: page === 'workbench' }">Workbench</Link>
            <Link :href="route('wiki.show', 'contributing')" :class="{ active: page === 'contributing' }">Contributing</Link>
        </div>

        <div class="nav-wiki-section">
            <a>the Language</a>
            <Link :href="route('wiki.show', 'ajp')" :class="{ active: page === 'ajp' }">Palestinian Arabic</Link>
            <Link :href="route('wiki.show', 'dialects')" :class="{ active: page === 'dialects' }">Dialects</Link>
        </div>

        <div class="nav-wiki-section">
            <Link :href="route('wiki.show', 'phonology')" :class="{ active: page === 'phonology' }">Phonology</Link>
            <Link :href="route('wiki.show', 'vowels')" :class="{ active: page === 'vowels' }">Vowels</Link>
            <Link :href="route('wiki.show', 'consonants')" :class="{ active: page === 'consonants' }">Consonants</Link>
            <a>Syllables</a>
            <a>Phonotactics</a>
        </div>

        <div class="nav-wiki-section">
            <a>Morphology</a>
            <Link :href="route('wiki.show', 'roots')" :class="{ active: page === 'roots' }">Roots</Link>
            <Link :href="route('wiki.show', 'patterns')" :class="{ active: page === 'patterns' }">Patterns</Link>
            <Link :href="route('wiki.show', 'inflections')" :class="{ active: page === 'affixes' }">Inflections</Link>
        </div>

        <div class="nav-wiki-section">
            <Link :href="route('wiki.show', 'syntax')" :class="{ active: page === 'syntax' }">Syntax</Link>
            <Link :href="route('wiki.show', 'verbs')" :class="{ active: page === 'verbs' }">Verbs</Link>
            <Link :href="route('wiki.show', 'nouns')" :class="{ active: page === 'nouns' }">Nouns</Link>
            <Link :href="route('wiki.show', 'adjectives')" :class="{ active: page === 'adjectives' }">Adjectives</Link>
            <Link :href="route('wiki.show', 'numerals')" :class="{ active: page === 'numerals' }">Numerals</Link>
            <Link :href="route('wiki.show', 'adverbs')" :class="{ active: page === 'adverbs' }">Adverbs</Link>
            <Link :href="route('wiki.show', 'prepositions')" :class="{ active: page === 'prepositions' }">Prepositions</Link>
            <Link :href="route('wiki.show', 'conjunctions')" :class="{ active: page === 'conjunctions' }">Conjunctions</Link>
            <Link :href="route('wiki.show', 'determiners')" :class="{ active: page === 'determiners' }">Determiners</Link>
            <Link :href="route('wiki.show', 'particles')" :class="{ active: page === 'particles' }">Particles</Link>
            <Link :href="route('wiki.show', 'phrases')" :class="{ active: page === 'phrases' }">Phrases</Link>
            <a>Clause Types</a>
        </div>
    </div>
</template>
