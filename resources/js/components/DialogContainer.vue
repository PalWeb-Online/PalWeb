<script setup>
import {useDialog} from "../composables/Dialog.js";
import SentenceItem from "./SentenceItem.vue";
import DialogActions from "./Actions/DialogActions.vue";
import {route} from "ziggy-js";
import AppTip from "./AppTip.vue";
import {ref} from "vue";
import ToggleSingle from "./ToggleSingle.vue";
import LoadingSpinner from "../Shared/LoadingSpinner.vue";

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

            <div class="window-section-head">
                <h2>transcript</h2>
            </div>
            <ToggleSingle v-model="showTerms" label="Show Terms"/>
            <ToggleSingle v-model="showTranscription" label="Show Transcription"/>

            <LoadingSpinner v-if="isLoadingTerms"/>

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
