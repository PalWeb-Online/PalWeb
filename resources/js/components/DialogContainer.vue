<script setup>
import {useDialog} from "../composables/Dialog.js";
import SentenceItem from "./SentenceItem.vue";
import DialogActions from "./Actions/DialogActions.vue";
import {route} from "ziggy-js";
import {useUserStore} from "../stores/UserStore.js";

const UserStore = useUserStore();

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
    id: Number,
});

const {data} = useDialog(props);
</script>

<template>
    <template v-if="! data.isLoading">
        <div class="window-container">
            <div class="window-header">
                <Link :href="route('dialogs.index')" class="material-symbols-rounded">home</Link>
                <div class="window-header-url">www.palweb.app/academy/dialogs/{dialog}</div>
            </div>
            <div class="window-section-head">
                <h1>dialog</h1>
                <DialogActions :model="data.dialog"/>
            </div>
            <div class="window-content-head">
                <div class="window-content-head-title" style="direction: rtl">{{ data.dialog.title }}</div>
                <div class="dialog-description" v-if="data.dialog.description">{{ data.dialog.description }}</div>
            </div>

            <template v-if="data.dialog.media">
                <div class="window-section-head">
                    <h2>media</h2>
                </div>
                <iframe :src="data.dialog.media" allowfullscreen></iframe>
            </template>

            <div class="window-section-head">
                <h2>transcript</h2>
            </div>
            <div class="dialog-body">
                <template v-for="sentence in data.dialog.sentences">
                    <SentenceItem :id="'position-' + sentence.position" :model="sentence" speaker/>
                </template>
            </div>
        </div>
    </template>
</template>
