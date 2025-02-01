<script setup>
import {route} from "ziggy-js";
import {useStateStore} from "../stores/StateStore.js";

const StateStore = useStateStore();

const props = defineProps({
    sentence: Object,
    size: {type: String, default: 'm'},
    speaker: Boolean,
    dialog: Boolean,
});
</script>

<template>
    <div :class="['sentence-item-wrapper', size]" style="grid-template-columns: 1fr">
        <div class="sentence-item">
            <template v-if="StateStore.data.step === 'dialog'">
                <input v-model="sentence.speaker" class="sentence-speaker"/>
            </template>
            <template v-if="StateStore.data.step === 'sentence'">
                <div v-if="speaker" class="sentence-speaker">{{ sentence.speaker }}</div>
            </template>

            <div v-if="sentence.terms.length > 0" class="sentence-arb">
                <template v-for="term in sentence.terms">
                    <div class="sentence-term">
                        <div>{{ term.sentencePivot.sent_term }}</div>
                        <div>{{ term.sentencePivot.sent_translit }}</div>
                    </div>
                </template>
            </div>

            <template v-if="StateStore.data.step === 'dialog'">
                <div class="sentence-eng">{{ sentence.trans }}</div>
            </template>
            <template v-if="StateStore.data.step === 'sentence'">
                <input class="sentence-eng" v-model="sentence.trans"/>
            </template>
        </div>

        <!--        todo: what if clicking this loads the Dialog & changes the model type to Dialog? -->
        <div v-if="dialog && sentence.dialog" class="sentence-dialog" style="grid-column: 1">
            <div>Dialog</div>
            <a :href="route('dialogs.edit', sentence.dialog.id)">{{ sentence.dialog.title }}</a>
        </div>
    </div>
</template>
