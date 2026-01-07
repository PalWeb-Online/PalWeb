<script setup>
import {useDialog} from "../composables/Dialog.js";
import DialogActions from "./Actions/DialogActions.vue";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {computed, ref} from "vue";
import AppTooltip from "./AppTooltip.vue";

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
    target: {type: String, default: 'library'},
});

const tooltip = ref(null);

const requestTarget = computed(() => {
    return props.target === 'library'
        ? route('dialogs.show', dialog?.id)
        : route('speech-maker.dialog', dialog?.id);
})

const {dialog, isLoading} = useDialog(props);
</script>

<template>
    <template v-if="!isLoading">
        <div class="model-item-container dialog-item-container">
            <div v-if="!dialog.unlocked" class="model-item-overlay"
                 @mousemove="tooltip.showTooltip('You haven\'t unlocked this Dialog in the Academy yet.', $event);"
                 @mouseleave="tooltip.hideTooltip()"></div>

            <div class="model-item dialog-item">
                <div class="model-item-content" @click="router.get(requestTarget)">
                    <div class="model-item-title">
                        {{ dialog.title }}
                    </div>
                </div>
                <DialogActions :model="dialog"/>
            </div>

            <div v-if="dialog.description" class="model-item-description">
                {{ dialog.description }}
            </div>
        </div>
        <AppTooltip ref="tooltip"/>
    </template>
</template>

<style scoped lang="scss">
.dialog-item {
    .model-item-content {
        padding: 1.0rem 1.6rem;
        justify-content: flex-end;

        .model-item-title {
            font-size: 1.8rem;
            direction: rtl;
        }

        .model-item-description {
            font-size: 1.4rem;
        }
    }
}
</style>
