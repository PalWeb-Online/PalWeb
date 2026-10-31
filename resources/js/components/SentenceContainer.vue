<script setup>
import {useSentence} from "../composables/sentences/useSentence.js";
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

const {sentence, isLoading} = useSentence(props);
</script>

<template>
    <template v-if="! isLoading">
        <div class="window-container">
            <div class="window-header">
                <RouterLink :to="{ name: 'sentences.index' }" class="material-symbols-rounded">home</RouterLink>
                <div class="window-header-url">www.palweb.app/library/sentences/{sentence}</div>
                <RouterLink :to="{ name: 'sentences.random' }" class="material-symbols-rounded">keyboard_double_arrow_right
                </RouterLink>
            </div>
            <div class="window-section-head">
                <h1>{{ $t('components.sentence.title') }}</h1>
                <PinButton modelType="sentence" :model="sentence"/>
                <SentenceActions :model="sentence"/>
            </div>
            <div class="model-item-container sentence-item-container l">
                <div v-if="sentence.dialog" class="sentence-dialog-data">
                    <RouterLink :to="{ name: 'dialogs.show', params: { dialog: sentence.dialog.id }, hash: '#position-' + sentence.position}">
                        <div>{{ $t('components.dialog.title') }}</div>
                        <div>{{ sentence.dialog.title }}</div>
                    </RouterLink>
                    <div>
                        <div>{{ $t('components.sentence.speaker') }}</div>
                        <div>{{ sentence.speaker }}</div>
                    </div>
                </div>
                <div class="model-item sentence-item">
                    <div class="model-item-content">
                        <template v-if="sentence.terms.length > 0" v-for="term in sentence.terms">
                            <template v-if="term.id">
                                <RouterLink class="sentence-term" :to="{ name: 'terms.show', params: { term: term.slug } }">
                                    <div>{{ term.sentencePivot.sent_term }}</div>
                                    <div>{{ term.sentencePivot.sent_translit }}</div>
                                </RouterLink>
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
                                <div>{{ sentence.sentence }}</div>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="model-item-description">
                    {{ sentence.trans }}
                </div>
            </div>
            <div class="window-section-head">
                <h2>{{ $t('components.common.sections.terms') }}</h2>
            </div>
            <div class="model-list">
                <TermItem v-for="term in sentence.terms.filter(t => t.id)"
                          :key="term.id"
                          :model="term"
                          :glossId="term.sentencePivot.gloss_id"
                />
            </div>
            <div class="terms-count">
                {{ $t('components.common.counts.terms', {count: sentence.terms.filter(t => t.id).length}) }}
            </div>
        </div>
    </template>
</template>
