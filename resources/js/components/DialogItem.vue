<script setup>
import {useDialog} from "../composables/Dialog.js";
import DialogActions from "./DialogActions.vue";

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
    id: Number,
    size: {type: String, default: 'm'},
});

const { data } = useDialog(props);
</script>

<template>
    <template v-if="! data.isLoading">
        <div class="deck-item-wrapper">
            <DialogActions :model="data.dialog"/>
            <div class="dialog-item">
                <div class="item-data">
                    <div class="item-title">
                        {{ data.dialog.title }}
                    </div>
                    <div v-if="data.dialog.description" class="item-description">
                        {{ data.dialog.description }}
                    </div>
                </div>
            </div>
        </div>
    </template>
</template>
