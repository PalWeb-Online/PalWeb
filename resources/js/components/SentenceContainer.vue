<script setup>
import {route} from 'ziggy-js';
import {useSentence} from "../composables/Sentence.js";
import PinButton from "./PinButton.vue";
import SentenceActions from "./Actions/SentenceActions.vue";
import TermItem from "./TermItem.vue";

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
});

const {data, playAudio} = useSentence(props);
</script>

<template>
    <template v-if="! data.isLoading">
        <div class="window-container">
            <div class="window-header">
                <Link :href="route('sentences.index')" class="material-symbols-rounded">home</Link>
                <div class="window-header-url">www.palweb.app/library/sentences/{sentence}</div>
                <Link :href="route('sentences.random')" class="material-symbols-rounded">keyboard_double_arrow_right
                </Link>
            </div>
            <div class="window-section-head">
                <h1>sentence</h1>
                <PinButton modelType="sentence" :model="data.sentence"/>
                <SentenceActions :model="data.sentence"/>
            </div>
            <div class="sentence-container-body">
                <div v-if="data.sentence.dialog" class="sentence-dialog-data">
                    <Link :href="route('dialogs.show', data.sentence.dialog.id) + '#position-' + data.sentence.position"
                          target="_blank">
                        <div>dialog</div>
                        <div>{{ data.sentence.dialog.title }}</div>
                    </Link>
                    <div>
                        <div>speaker</div>
                        <div>{{ data.sentence.speaker }}</div>
                    </div>
                </div>
                <div class="sentence-arb">
                    <template v-if="data.sentence.terms.length > 0" v-for="term in data.sentence.terms">
                        <template v-if="term.id">
                            <Link class="sentence-term" :href="route('terms.show', term.slug)" target="_blank">
                                <div>{{ term.sentencePivot.sent_term }}</div>
                                <div>{{ term.sentencePivot.sent_translit }}</div>
                            </Link>
                        </template>
                        <template v-else>
                            <div class="sentence-term">
                                <div>{{ term.sentencePivot.sent_term }}</div>
                                <div>{{ term.sentencePivot.sent_translit }}</div>
                            </div>
                        </template>
                    </template>
                    <template v-else>
                        <div class="sentence-term" style="background: none">
                            <div>{{ data.sentence.sentence }}</div>
                        </div>
                    </template>
                </div>
                <div class="sentence-eng">
                    {{ data.sentence.trans }}
                </div>
            </div>
            <div class="window-section-head">
                <h2>terms</h2>
            </div>
            <div class="model-list index-list">
                <TermItem v-for="term in data.sentence.terms.filter(term => term.id)"
                          :key="term.id"
                          :model="term"
                          :glossId="term.sentencePivot.gloss_id"
                />
            </div>
            <div class="terms-count">{{ data.sentence.terms.filter(term => term.id).length }} Terms</div>
        </div>
    </template>
</template>
