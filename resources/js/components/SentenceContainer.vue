<script setup>
import {route} from 'ziggy-js';
import {useSentence} from "../composables/Sentence.js";
import PinButton from "./PinButton.vue";
import SentenceActions from "./Actions/SentenceActions.vue";
import TermItem from "./TermItem.vue";
import {useUserStore} from "../stores/UserStore.js";

const UserStore = useUserStore();

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
});

const {sentence, isLoading, isPlaying, playAudio} = useSentence(props);
</script>

<template>
    <template v-if="! isLoading">
        <div class="window-container">
            <div class="window-header">
                <Link :href="route('sentences.index')" class="material-symbols-rounded">home</Link>
                <div class="window-header-url">www.palweb.app/library/sentences/{sentence}</div>
                <Link :href="route('sentences.random')" class="material-symbols-rounded">keyboard_double_arrow_right
                </Link>
            </div>
            <div class="window-section-head">
                <h1>sentence</h1>
                <PinButton modelType="sentence" :model="sentence"/>
                <SentenceActions :model="sentence"/>
            </div>
            <div class="model-item-container sentence-item-container l">
                <div v-if="sentence.dialog" class="sentence-dialog-data">
                    <Link :href="route('dialogs.show', sentence.dialog.id) + '#position-' + sentence.position"
                          target="_blank">
                        <div>dialog</div>
                        <div>{{ sentence.dialog.title }}</div>
                    </Link>
                    <div>
                        <div>speaker</div>
                        <div>{{ sentence.speaker }}</div>
                    </div>
                </div>
                <div class="model-item sentence-item">
                    <div class="model-item-content">
                        <template v-if="sentence.terms.length > 0" v-for="term in sentence.terms">
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
                <h2>terms</h2>
            </div>
            <div class="model-list index-list">
                <TermItem v-for="term in sentence.terms.filter(term => term.id)"
                          :key="term.id"
                          :model="term"
                          :glossId="term.sentencePivot.gloss_id"
                />
            </div>
            <div class="terms-count">{{ sentence.terms.filter(term => term.id).length }} Terms</div>
        </div>
    </template>
</template>
