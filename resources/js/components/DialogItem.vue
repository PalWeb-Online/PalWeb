<script setup>
import {useDialog} from "../composables/Dialog.js";
import DialogActions from "./Actions/DialogActions.vue";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {computed} from "vue";

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
    id: Number,
    target: {type: String, default: 'library'},
});

const requestTarget = computed(() => {
    return props.target === 'library'
        ? route('dialogs.show', data.dialog?.id)
        : route('speech-maker.dialog', data.dialog?.id);
})

const {data} = useDialog(props);
</script>

<template>
    <template v-if="! data.isLoading">
        <div class="deck-item-container">
            <div class="dialog-item">
                <div class="model-item-content" @click="router.get(requestTarget)">
                    <div class="model-item-title">
                        {{ data.dialog.title }}
                    </div>
                </div>
                <DialogActions :model="data.dialog"/>
            </div>

            <div v-if="data.dialog.description" class="model-item-description">
                {{ data.dialog.description }}
            </div>
        </div>
    </template>
</template>
