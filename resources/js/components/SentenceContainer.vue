<script setup>
import {Link} from '@inertiajs/inertia-vue3'
import {route} from 'ziggy-js';
import {useSentence} from "../composables/Sentence.js";
import PinButton from "./PinButton.vue";
import SentenceActions from "./SentenceActions.vue";
import TermItem from "./TermItem.vue";

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
            <PinButton modelType="sentence" :model="data.sentence"/>
            <SentenceActions :model="data.sentence"/>
            <div class="sentence-item">
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
                        <div class="sentence-term">
                            <div>{{ data.sentence.sentence }}</div>
                        </div>
                    </template>
                </div>

<!--                todo: make this not selectable when Sentence is small -->
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
        </div>

        <!--        todo: terms list is collapsible -->
        <div v-if="size === 'l'" class="terms-list">
            <TermItem v-for="term in data.sentence.terms" :key="term.id" :model="term"/>
        </div>
    </template>
</template>
