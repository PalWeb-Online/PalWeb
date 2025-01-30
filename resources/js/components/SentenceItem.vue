<script setup>
import {route} from 'ziggy-js';
import {useSentence} from "../composables/Sentence.js";
import PinButton from "./PinButton.vue";
import SentenceActions from "./SentenceActions.vue";

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
    id: Number,
    size: {type: String, default: 'm'},
    currentTerm: Number,
    speaker: Boolean,
    dialog: Boolean,
});

const {data, isCurrentTerm, playAudio} = useSentence(props);
</script>

<template>
    <template v-if="! data.isLoading">
        <div :class="['sentence-item-wrapper', size]">
            <SentenceActions :model="data.sentence"/>
            <div class="sentence-item">
                <div v-if="speaker" class="sentence-speaker">
                    {{ data.sentence.speaker }}
                    <img class="play" src="/img/audio.svg" alt="play" @click="playAudio"/>
                </div>
                <div class="sentence-arb">
                    <template v-for="term in data.sentence.terms">
                        <template v-if="term.id">
                            <a :href="isCurrentTerm(term) ? '#' : route('terms.show', term.slug)"
                               :target="isCurrentTerm(term) ? '' : '_blank'"
                               :class="['sentence-term', isCurrentTerm(term) ? 'active' : '']">
                                <div>{{ term.sentencePivot.sent_term }}</div>
                                <div>{{ term.sentencePivot.sent_translit }}</div>
                            </a>
                        </template>
                        <template v-else>
                            <div class="sentence-term">
                                <div>{{ term.sentencePivot.sent_term }}</div>
                                <div>{{ term.sentencePivot.sent_translit }}</div>
                            </div>
                        </template>
                    </template>
                </div>
                <div class="sentence-eng">
                    {{ data.sentence.trans }}
                </div>
            </div>
            <div v-if="dialog && data.sentence.dialog" class="sentence-dialog">
                <div>Dialog</div>
                <a :href="route('dialogs.show', data.sentence.dialog.id )" target="_blank">
                    {{ data.sentence.dialog.title }}
                </a>
            </div>
            <PinButton modelType="sentence" :model="data.sentence"/>
        </div>
    </template>
</template>
