<script setup>
import {route} from 'ziggy-js';
import {useSentence} from "../composables/Sentence.js";
import PinButton from "./PinButton.vue";
import SentenceActions from "./SentenceActions.vue";

const props = defineProps({
    id: Number,
    size: {type: String, default: 'm'},
    currentTerm: Number,
});

const { data, isCurrentTerm, playAudio } = useSentence(props);
</script>

<template>
    <template v-if="! data.isLoading">
        <div class="sentence-wrapper">
            <div :class="['sentence-item', size]" @click="playAudio">
                <div class="sentence-arb">
                    <template v-for="term in data.sentence.terms">
                        <template v-if="term.id">
                            <a :href="isCurrentTerm(term) ? '#' : route('terms.show', term.slug)"
                               :target="isCurrentTerm(term) ? '' : '_blank'"
                               :class="['sentence-term', isCurrentTerm(term) ? 'active' : '']">
                                <div>{{ term.pivot.sent_term }}</div>
                                <div>{{ term.pivot.sent_translit }}</div>
                            </a>
                        </template>
                        <template v-else>
                            <div class="sentence-term">
                                <div>{{ term.pivot.sent_term }}</div>
                                <div>{{ term.pivot.sent_translit }}</div>
                            </div>
                        </template>
                    </template>
                </div>
                <div class="sentence-eng">
                    {{ data.sentence.trans }}
                </div>
                <PinButton modelType="sentence" :model="data.sentence"/>
            </div>
            <SentenceActions :model="data.sentence"/>
        </div>
    </template>
</template>
