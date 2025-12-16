<script setup>
import {useSentence} from "../composables/Sentence.js";
import PinButton from "./PinButton.vue";
import SentenceActions from "./Actions/SentenceActions.vue";
import {nextTick, onMounted} from "vue";
import {route} from "ziggy-js";
import {useUserStore} from "../stores/UserStore.js";
import {router} from "@inertiajs/vue3";

const UserStore = useUserStore();

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
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

const {sentence, isLoading, isPlaying, isCurrentTerm, playAudio} = useSentence(props);
</script>

<template>
    <template v-if="! isLoading">
        <div class="model-item-container sentence-item-container">
            <div class="sentence-dialog-data" v-if="(dialog && sentence.dialog) || speaker">
                <Link v-if="dialog && sentence.dialog"
                      :href="route('dialogs.show', sentence.dialog.id) + '#position-' + sentence.position"
                      target="_blank">
                    <div>dialog</div>
                    <div>{{ sentence.dialog.title }}</div>
                </Link>
                <div v-if="speaker">
                    <div>speaker</div>
                    <div>{{ sentence.speaker }}</div>
                    <!--                    <img class="play" src="/img/audio.svg" alt="play" @click="playAudio"/>-->
                </div>
            </div>
            <div class="model-item sentence-item">
                <PinButton modelType="sentence" :model="sentence"/>
                <div class="model-item-content">
                    <template v-if="sentence.terms.length > 0" v-for="term in sentence.terms">
                        <Link v-if="term.id"
                              :href="isCurrentTerm(term) ? '#' : route('terms.show', term.slug)"
                              :target="isCurrentTerm(term) ? '' : '_blank'"
                              :class="['sentence-term', isCurrentTerm(term) ? 'active' : '']">
                            <div>{{ term.sentencePivot.sent_term }}</div>
                        </Link>
                        <div v-else class="sentence-term">
                            <div>{{ term.sentencePivot.sent_term }}</div>
                        </div>
                    </template>
                    <div v-else class="sentence-term" style="background: none">
                        <div>{{ sentence.sentence }}</div>
                    </div>
                </div>
                <SentenceActions v-if="UserStore.isAdmin" :model="sentence"/>
                <div v-else class="popup-menu-wrapper">
                    <button class="material-symbols-rounded" @click="router.get(route('sentences.show', sentence.id))">
                        visibility
                    </button>
                </div>
            </div>
            <div class="model-item-description">
                {{ sentence.trans }}
            </div>
        </div>
    </template>
</template>
