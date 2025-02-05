<script setup>
import {onMounted} from "vue";
import {cloneDeep} from "lodash";
import {useStateStore} from "../../components/dialogs/stores/StateStore.js";
import {useDialogStore} from "../../components/dialogs/stores/DialogStore.js";
import {useSentenceStore} from "../../components/dialogs/stores/SentenceStore.js";
import Dialog from "../../components/dialogs/pages/Dialog.vue";
import Sentence from "../../components/dialogs/pages/Sentence.vue";
import Layout from "../../Shared/Layout.vue";

const StateStore = useStateStore();
const DialogStore = useDialogStore();
const SentenceStore = useSentenceStore();

const props = defineProps({
    mode: String,
    stagedModel: Object,
});


onMounted(async () => {
    StateStore.data.mode = props.mode;

    if (StateStore.data.mode === 'dialog') {
        if (props.stagedModel) {
            DialogStore.data.stagedDialog = props.stagedModel;
        }

        DialogStore.data.originalDialog = cloneDeep(DialogStore.data.stagedDialog);

    } else if (StateStore.data.mode === 'sentence') {
        if (props.stagedModel) {
            SentenceStore.data.stagedSentence = props.stagedModel;
            SentenceStore.data.originalSentence = cloneDeep(SentenceStore.data.stagedSentence);
        }

        StateStore.data.step = 'sentence';
    }
});

defineOptions({
    layout: Layout
});
</script>

<template>
    <Head title="Dialogger"/>
    <div id="app-head">
        <h1>Dialogger</h1>
    </div>

    <div id="app-body">
        <div id="dm-build" v-if="StateStore.data.step === 'dialog'">
            <Dialog/>
        </div>
        <div id="dm-build" v-if="StateStore.data.step === 'sentence'">
            <Sentence/>
        </div>
    </div>
</template>
