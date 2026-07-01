<script setup>
import Layout from "../../../Shared/Layout.vue";
import DialogItem from "../../../components/DialogItem.vue";
import TermItem from "../../../components/TermItem.vue";
import {ref} from "vue";
import AppTip from "../../../components/AppTip.vue";
import {router} from "@inertiajs/vue3";

defineOptions({
    layout: Layout
});

const props = defineProps({
    dialogs: {type: Array, default: []},
    terms: {type: Array, default: []}
});

const mode = ref('dialog');

const toBuild = () => {
    mode.value === 'dialog'
        ? router.get(route('speech-maker.dialog'))
        : router.get(route('speech-maker.sentence'));
}
</script>

<template>
    <Head title="Speech Maker"/>

    <div id="app-head">
        <h1>Speech Maker</h1>
    </div>
    <div id="app-body">
        <div id="sm-select">
            <div class="sm-mode-select">
                <button class="featured-title l" :class="{'active': mode === 'dialog'}"
                        @click="mode = 'dialog'">dlg
                </button>
                <div :class="mode">
                    <button class="material-symbols-rounded" @click="toBuild()">
                        add
                    </button>
                </div>
                <button class="featured-title l" :class="{'active': mode === 'sentence'}"
                        @click="mode = 'sentence'">snt
                </button>
            </div>

            <div v-if="mode === 'dialog'" class="model-list">
                <DialogItem v-for="dialog in dialogs" :model="dialog" target="workbench"/>
            </div>
            <div v-else-if="mode === 'sentence'" class="model-list">
                <AppTip>
                    <p>Need some inspiration? Here are 25 random Terms that have no Sentences for one of their
                        Glosses. (The Controller could be adjusted so that the TermItem specifies the Gloss that doesn't
                        have a Sentence, as right now the default Gloss is shown.)</p>
                </AppTip>
                <TermItem v-for="term in terms" :model="term"/>
            </div>
        </div>
    </div>
</template>
