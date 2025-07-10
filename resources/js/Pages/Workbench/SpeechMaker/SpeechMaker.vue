<script setup>
import {route} from "ziggy-js";
import {router} from "@inertiajs/vue3";
import Layout from "../../../Shared/Layout.vue";
import Dialog from "./pages/Dialog.vue";
import Sentence from "./pages/Sentence.vue";
import AppButton from "../../../components/AppButton.vue";
import DialogItem from "../../../components/DialogItem.vue";

const props = defineProps({
    step: {type: String, default: 'select'},
    mode: {type: String, default: null},
    dialog: Object,
    sentence: Object,
    collection: {type: Array, default: []}
});

defineOptions({
    layout: Layout
});
</script>

<template>
    <Head title="Speech Maker"/>

    <template v-if="step === 'select'">
        <div id="app-head">
            <h1>Speech Maker</h1>
            <div id="app-nav">
                <div class="app-mode-buttons">
                    <AppButton @click="router.get(route('speech-maker.dialog'))" label="dialog"/>
                    <AppButton @click="router.get(route('speech-maker.sentence'))" label="sentence"/>
                </div>
            </div>
        </div>
        <div id="app-body">
            <div class="model-list">
                <DialogItem v-for="dialog in collection" :model="dialog"/>
            </div>
        </div>
    </template>
    <div id="app-body" v-else>
        <template v-if="step === 'build'">
            <Dialog v-if="mode === 'dialog'"
                    :dialog="dialog"
            />
            <Sentence v-if="mode === 'sentence'"
                      :dialog="dialog"
                      :sentence="sentence"
            />
        </template>
    </div>
</template>
