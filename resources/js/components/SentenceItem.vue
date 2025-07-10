<script setup>
import {route} from 'ziggy-js';
import {useSentence} from "../composables/Sentence.js";
import PinButton from "./PinButton.vue";
import SentenceActions from "./SentenceActions.vue";
import {nextTick, onMounted} from "vue";

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
    size: {type: String, default: 'm'},
    currentTerm: Number,
    speaker: Boolean,
    dialog: Boolean,
});

onMounted(() => {
    const hash = window.location.hash;

    if (hash) {
        nextTick(() => {
            const el = document.querySelector(hash);

            if (el) {
                el.classList.add('highlighted');
            }
        });
    }
});

const {data, isCurrentTerm, playAudio} = useSentence(props);
</script>

<template>
    <template v-if="! data.isLoading">
        <div :class="['sentence-item-wrapper', size]">
            <SentenceActions :model="data.sentence" icon="emoji"/>
            <div class="sentence-item">
                <PinButton modelType="sentence" :model="data.sentence" floating/>
                <div v-if="speaker" class="sentence-speaker">
                    {{ data.sentence.speaker }}
                    <img class="play" src="/img/audio.svg" alt="play" @click="playAudio"/>
                </div>
                <div class="sentence-arb">
                    <template v-if="data.sentence.terms.length > 0" v-for="term in data.sentence.terms">
                        <template v-if="term.id">
                            <Link :href="isCurrentTerm(term) ? '#' : route('terms.show', term.slug)"
                                  :target="isCurrentTerm(term) ? '' : '_blank'"
                                  :class="['sentence-term', isCurrentTerm(term) ? 'active' : '']">
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
            <div v-if="dialog && data.sentence.dialog" class="sentence-dialog">
                <div>Dialog</div>
                <Link :href="route('dialogs.show', data.sentence.dialog.id) + '#position-' + data.sentence.position"
                      target="_blank">
                    {{ data.sentence.dialog.title }}
                </Link>
            </div>
        </div>
    </template>
</template>
