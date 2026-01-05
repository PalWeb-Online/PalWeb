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

                <!--                    <form method="POST" action="{{ route('missing.terms.destroy', $missingInflection) }}">-->
                <!--                        @csrf-->
                <!--                        @method('DELETE')-->
                <!--                        <button onclick="return confirm('Are you sure you want to delete this sentence?')">-->
                <!--                            <img src="{{ asset('/img/trash.svg') }}" alt="Delete"/>-->
                <!--                        </button>-->
                <!--                    </form>-->
            </div>
        </div>
    </div>
</template>
