<script setup>
import {route} from "ziggy-js";
</script>
<template>
    <div class="nav-wiki">
        <Link :href="route('wiki.show', 'faq')">FAQ</Link>
        <Link :href="route('wiki.show', 'about')">About</Link>
        <Link :href="route('wiki.show', 'release-notes')">Release Notes</Link>

        <div class="nav-wiki-section">
            <Link :href="route('wiki.show', 'user-guide')">User Guide</Link>
            <Link :href="route('wiki.show', 'academy')">Academy</Link>
            <Link :href="route('wiki.show', 'dictionary')">Dictionary</Link>
            <Link :href="route('wiki.show', 'workbench')">Workbench</Link>
            <Link :href="route('wiki.show', 'contributing')">Contributing</Link>
        </div>

        <div class="nav-wiki-section">
            <a>the Language</a>
            <Link :href="route('wiki.show', 'ajp')">Palestinian Arabic</Link>
            <Link :href="route('wiki.show', 'dialects')">Dialects</Link>
        </div>

        <div class="nav-wiki-section">
            <Link :href="route('wiki.show', 'phonology')">AJP: Phonology</Link>
            <Link :href="route('wiki.show', 'vowels')">Vowels</Link>
            <Link :href="route('wiki.show', 'consonants')">Consonants</Link>
            <a>Syllables & Stress</a>
            <a>Phonotactics</a>
        </div>

        <div class="nav-wiki-section">
            <a>AJP: Morphology</a>
            <Link :href="route('wiki.show', 'roots')">Roots</Link>
            <Link :href="route('wiki.show', 'patterns')">Patterns</Link>
            <Link :href="route('wiki.show', 'verb-forms')">Verb Forms</Link>
            <Link :href="route('wiki.show', 'affixes')">Affixes</Link>
        </div>

        <div class="nav-wiki-section">
            <Link :href="route('wiki.show', 'syntax')">AJP: Syntax</Link>
            <Link :href="route('wiki.show', 'verbs')">Verbs</Link>
            <Link :href="route('wiki.show', 'nouns')">Nouns</Link>
            <Link :href="route('wiki.show', 'adjectives')">Adjectives</Link>
            <Link :href="route('wiki.show', 'numerals')">Numerals</Link>
            <Link :href="route('wiki.show', 'adverbs')">Adverbs</Link>
            <Link :href="route('wiki.show', 'prepositions')">Prepositions</Link>
            <Link :href="route('wiki.show', 'conjunctions')">Conjunctions</Link>
            <Link :href="route('wiki.show', 'determiners')">Determiners</Link>
            <Link :href="route('wiki.show', 'particles')">Particles</Link>
            <Link :href="route('wiki.show', 'phrases')">Phrases</Link>
            <a>Clause Types</a>
        </div>
    </div>
</template>
