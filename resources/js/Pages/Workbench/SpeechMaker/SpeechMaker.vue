<script setup>
import {route} from "ziggy-js";
import Layout from "../../../Shared/Layout.vue";
import Dialog from "./pages/Dialog.vue";
import Sentence from "./pages/Sentence.vue";
import AppButton from "../../../components/AppButton.vue";
import {Inertia} from "@inertiajs/inertia";

const props = defineProps({
    mode: String,
    model: String,
    dialog: Object,
    sentence: Object,
});

defineOptions({
    layout: Layout
});
</script>

<template>
    <Head title="Speech Maker"/>
    <div id="app-head">
        <h1>Speech Maker</h1>
        <div id="app-nav">
            <div :class="['app-mode-buttons', mode]">
                <AppButton @click="Inertia.get(route('speech-maker.dialog'))" :active="mode === 'dialog'"
                           label="dialog"/>
                <AppButton @click="Inertia.get(route('speech-maker.sentence'))" :active="mode === 'sentence'"
                           label="sentence"/>
            </div>
        </div>
    </div>

    <div id="app-body" :class="{
        'sm-mode-sentence': model === 'sentence',
        'sm-mode-dialog': model === 'dialog'
    }">
        <Dialog v-if="model === 'dialog'"
                :dialog="dialog"
        />
        <Sentence v-if="model === 'sentence'"
                  :dialog="dialog"
                  :sentence="sentence"
        />
    </div>
</template>
