<script setup>
import Layout from "../../../Shared/Layout.vue";
import Dialog from "./pages/Dialog.vue";
import Sentence from "./pages/Sentence.vue";
import DialogItem from "../../../components/DialogItem.vue";
import TermItem from "../../../components/TermItem.vue";
import {ref} from "vue";
import AppTip from "../../../components/AppTip.vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    step: {type: String, default: 'select'},
    mode: {type: String, default: 'dialog'},
    dialog: Object,
    sentence: Object,
    allDialogs: {type: Array, default: []},
    termsMissingSentences: {type: Array, default: []}
});

const mode = ref(props.mode);
const step = ref(props.step);
</script>

<template>
    <Head title="Speech Maker"/>

    <div id="app-head" v-if="step === 'select'">
        <h1>Speech Maker</h1>
    </div>

    <div id="app-body">
        <div id="sm-select" v-if="step === 'select'">
            <div class="sm-mode-select">
                <button class="featured-title l" :class="{'active': mode === 'dialog'}"
                        @click="mode = 'dialog'">dlg
                </button>
                <div :class="mode">
                    <button class="material-symbols-rounded" @click="step = 'build'">
                        add
                    </button>
                </div>
                <button class="featured-title l" :class="{'active': mode === 'sentence'}"
                        @click="mode = 'sentence'">snt
                </button>
            </div>

            <div v-if="mode === 'dialog'" class="model-list">
                <DialogItem v-for="dialog in allDialogs" :model="dialog" target="workbench"/>
            </div>
            <div v-else-if="mode === 'sentence'" class="model-list">
                <AppTip>
                    <p>Need some inspiration? Here are 25 random Terms that have no Sentences for one of their
                        Glosses. (The Controller could be adjusted so that the TermItem specifies the Gloss that doesn't
                        have a Sentence, as right now the default Gloss is shown.)</p>
                </AppTip>
                <TermItem v-for="term in termsMissingSentences" :model="term"/>
            </div>
        </div>
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
