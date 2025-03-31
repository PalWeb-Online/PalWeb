<script setup>
import {route} from "ziggy-js";
import Layout from "../../Shared/Layout.vue";

defineProps({
    feedbackComments: Array,
    fromSentences: Array,
    missingInflections: Array,
    termsMissingSentences: Array,
})

defineOptions({
    layout: Layout
})
</script>
<template>
    <Head title="to-Do List"/>
    <div id="app-head">
        <h1>To-Do</h1>
    </div>
    <div id="app-body">
        <div class="wiki-container">
            <h1>From Sentences</h1>
            <div class="missing-terms">
                <div v-for="term in fromSentences">
                    {{ term.sent_term }}
                    ({{ term.sent_translit }})

                    <Link :href="route('sentences.show', term.sentence_id)">View Sentence</Link>
                </div>
            </div>

            <h1>Missing Terms</h1>
            <div class="missing-terms">
                <div v-for="comment in feedbackComments">
                    {{ comment.comment }}

                    <!--                    <form method="POST" action="{{ route('missing.terms.destroy', $missingTerm) }}">-->
                    <!--                        @csrf-->
                    <!--                        @method('DELETE')-->
                    <!--                        <button onclick="return confirm('Are you sure you want to delete this item from the list?')">-->
                    <!--                            <a>Delete Missing</a>-->
                    <!--                        </button>-->
                    <!--                    </form>-->
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

            <h1>Terms Missing Sentences</h1>
            <ul>
                <li v-for="term in termsMissingSentences">
                    <Link :href="route('terms.show', term.slug)">
                        {{ term.term }}
                        ({{ term.translit }})
                        "{{ term.gloss }}"
                    </Link>
                </li>
            </ul>
        </div>
    </div>
</template>
