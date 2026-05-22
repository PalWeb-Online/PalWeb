<script setup>
import {route} from "ziggy-js";
import Layout from "../../../Shared/Layout.vue";

defineProps({
    fromSentences: Array,
    missingInflections: Array,
    termsMissingSentences: Array,
})

defineOptions({
    layout: Layout
})
</script>
<template>
    <Head title="Word Logger"/>
    <div id="app-head">
        <h1>Word Logger</h1>
    </div>
    <div id="app-body">
        <Link :href="route('word-logger.term')">+ Term</Link>
        <h1>From Sentences</h1>
        <div class="missing-terms">
            <div v-for="term in fromSentences">
                {{ term.sent_term }}
                ({{ term.sent_translit }})

                <Link :href="route('sentences.show', term.sentence_id)">View Sentence</Link>
            </div>
        </div>

        <h1>Missing Inflections</h1>
        <div class="missing-terms">
            <div v-for="term in missingInflections">
                {{ term.inflection }}
                {{ term.translit }}
                ({{ term.form }})
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.missing-terms {
    display: grid;
    gap: 0.2rem;

    & > div {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.4rem 0.8rem;
        border-radius: 0.4rem;

        &:hover {
            background: var(--color-pastel-light);
        }
    }

    button {
        display: flex;
        align-items: center;
        font-family: var(--body-font);
        font-size: 1.6rem;
    }
}
</style>
