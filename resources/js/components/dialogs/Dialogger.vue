<script setup>
import {onMounted} from "vue";
import {useStateStore} from "./stores/StateStore.js";
import {useDialogStore} from "./stores/DialogStore.js";
import {useSentenceStore} from "./stores/SentenceStore.js";
import Dialog from "./pages/Dialog.vue";
import Sentence from "./pages/Sentence.vue";

const StateStore = useStateStore();
const DialogStore = useDialogStore();
const SentenceStore = useSentenceStore();
StateStore.data.modelType = window.modelType;

onMounted(async () => {
    if (StateStore.data.modelType === 'dialog') {
        window.modelId && await DialogStore.fetchDialog(window.modelId);

    } else if (StateStore.data.modelType === 'sentence') {
        window.modelId && await SentenceStore.fetchSentence(window.modelId);

        StateStore.data.step = 'sentence';
    }
});
</script>

<template>
    <div id="app-head">
        <button @click="StateStore.exit">Exit to Dialog Library</button>
        <h1>Dialogger</h1>
        <div id="app-nav">
            <img :class="StateStore.backDisabled ? 'disabled' : ''" alt="Back" src="/img/finger-back.svg"
                 @click="StateStore.back"/>
            <div class="app-nav-steps">
                <div v-if="StateStore.data.modelType === 'dialog'"
                     :class="{ active: StateStore.data.step === 'dialog' }">Dialog
                </div>
                <div :class="{ active: StateStore.data.step === 'sentence' }">Sentence</div>
            </div>
            <img :class="StateStore.nextDisabled ? 'disabled' : ''" alt="Next" src="/img/finger-next.svg"
                 @click="StateStore.next"/>
        </div>
    </div>

    <div id="app-body">
        <div class="db-container" style="max-width: 96rem">
            <div id="db-page-build" v-if="StateStore.data.step === 'dialog'" style="justify-items: normal">
                <Dialog/>
            </div>
            <div id="db-page-build" v-if="StateStore.data.step === 'sentence'" style="justify-items: normal">
                <Sentence/>
            </div>
        </div>
    </div>
</template>
