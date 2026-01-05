<script setup>
import {useDialog} from "../composables/Dialog.js";
import SentenceItem from "./SentenceItem.vue";
import DialogActions from "./Actions/DialogActions.vue";
import {route} from "ziggy-js";
import AppTip from "./AppTip.vue";
import {ref} from "vue";
import ToggleSingle from "./ToggleSingle.vue";
import LoadingSpinner from "../Shared/LoadingSpinner.vue";
import LessonStatus from "./LessonStatus.vue";

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
});

const showTerms = ref(true);
const showTranscription = ref(false);

const {dialog, isLoading, isLoadingTerms} = useDialog(props);
</script>

<template>
    <template v-if="!isLoading">
        <div class="window-container">
            <div class="window-header">
                <Link :href="route('dialogs.index')" class="material-symbols-rounded">home</Link>
                <div class="window-header-url">www.palweb.app/academy/dialogs/{dialog}</div>
            </div>
            <div class="window-section-head">
                <h1>dialog</h1>
                <DialogActions :model="dialog"/>
            </div>
            <AppTip v-if="!dialog.published">
                <p>This Dialog is currently a Draft, so it will not be visible to others on the site; this page is
                    only visible to admins. </p>
            </AppTip>

            <LessonStatus v-if="dialog.lesson" :lesson="dialog.lesson" :model="dialog"/>
            <div class="window-content-head">
                <div class="window-content-head-title" style="direction: rtl">{{ dialog.title }}</div>
                <div class="dialog-description" v-if="dialog.description">{{ dialog.description }}</div>
            </div>

            <template v-if="dialog.media">
                <div class="window-section-head">
                    <h2>media</h2>
                </div>
                <iframe :src="dialog.media" allowfullscreen></iframe>
            </template>

            <template v-if="!isLoadingTerms">
                <div class="window-section-head">
                    <h2>options</h2>
                </div>
                <div class="settings-wrapper">
                    <ToggleSingle v-model="showTerms" label="Show Terms"/>
                    <ToggleSingle v-model="showTranscription" label="Show Transcription"/>
                </div>
            </template>

            <div class="window-section-head">
                <h2>transcript</h2>
            </div>
            <div class="loading-dialog" v-if="isLoadingTerms">
                <AppTip>
                    <p>Please wait for a moment while we load the data for all the Terms in every Sentence.</p>
                </AppTip>
                <LoadingSpinner/>
            </div>
            <div class="dialog-body">
                <template v-for="sentence in dialog.sentences">
                    <SentenceItem :id="'position-' + sentence.position" :model="sentence" speaker
                                  :showTerms="showTerms"
                                  :showTranscription="showTranscription"
                    />
                </template>
            </div>
        </div>
    </template>
</template>
