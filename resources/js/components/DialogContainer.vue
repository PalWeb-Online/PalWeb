<script setup>
import {useDialog} from "../composables/Dialog.js";
import SentenceItem from "./SentenceItem.vue";
import DialogActions from "./DialogActions.vue";

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
    id: Number,
});

const { data } = useDialog(props);
</script>

<template>
    <template v-if="! data.isLoading">
        <div class="dialog-container">
            <div class="dialog-container-head">
                <div class="dialog-container-head-title">{{ data.dialog.title }}</div>
                <DialogActions :model="data.dialog"/>
            </div>
            <iframe v-if="data.dialog.media" :src="data.dialog.media" allowfullscreen></iframe>
            <div class="dialog-description" v-if="data.dialog.description">{{ data.dialog.description }}</div>

            <div class="dialog-body">
                <template v-for="sentence in data.dialog.sentences">
                    <SentenceItem :model="sentence" speaker/>
                </template>
            </div>
        </div>
    </template>
</template>
