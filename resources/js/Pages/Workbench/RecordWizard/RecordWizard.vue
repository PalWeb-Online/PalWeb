<script setup>
import {computed, onBeforeUnmount, onMounted, watch} from "vue";
import {merge} from "lodash";
import Layout from "../../../Shared/Layout.vue";
import {useRecordWizardStore} from "./stores/RecordWizardStore.js";
import {useQueueStore} from "./stores/QueueStore.js";
import {useRecordStore} from "./stores/RecordStore.js";
import {useNavGuard} from "../../../composables/NavGuard.js";
import Speaker from "./pages/Speaker.vue";
import Queue from "./pages/Queue.vue";
import Record from "./pages/Record.vue";
import Check from "./pages/Check.vue";
import NavGuard from "../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";

const props = defineProps({
    speaker: Object,
});

defineOptions({
    layout: Layout
});

const RecordWizardStore = useRecordWizardStore();
const QueueStore = useQueueStore();
const RecordStore = useRecordStore();

const hasNavigationGuard = computed(() => {
    return RecordStore.data.statusCount.stashed > 0;
});

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

watch(
    () => props.speaker,
    (newValue) => {
        if (newValue) {
            Object.assign(RecordWizardStore.speaker, newValue);
        }
    },
    {deep: true}
);

onMounted(() => {
    if (props.speaker) {
        merge(RecordWizardStore.speaker, props.speaker);
    }
});

onBeforeUnmount(() => {
    RecordWizardStore.data.step = 'speaker';
    QueueStore.queue = [];

    if (RecordStore.recorder) {
        RecordStore.closeRecorder();
    }
});
</script>

<template>
    <Head title="Record Wizard"/>
    <div id="app-body">
        <div class="rw-container window-container">
            <div class="window-section-head">
                <h1>Record Wizard</h1>
            </div>
            <div class="window-page-nav">
                <button class="material-symbols-rounded" :disabled="RecordWizardStore.backDisabled"
                        @click="RecordWizardStore.back">arrow_back
                </button>
                <div class="material-symbols-rounded"
                     :class="{ active: RecordWizardStore.data.step === 'speaker', disabled: RecordWizardStore.data.step !== 'speaker' }">
                    voice_selection
                </div>
                <div class="material-symbols-rounded" :class="{ active: RecordWizardStore.data.step === 'queue' }">
                    list
                </div>
                <div class="material-symbols-rounded" :class="{ active: RecordWizardStore.data.step === 'record' }">
                    mic
                </div>
                <div class="material-symbols-rounded" :class="{ active: RecordWizardStore.data.step === 'check' }">
                    checklist
                </div>
                <button class="material-symbols-rounded" :disabled="RecordWizardStore.nextDisabled"
                        @click="RecordWizardStore.next">arrow_forward
                </button>
            </div>
            <Speaker v-if="RecordWizardStore.data.step === 'speaker'"/>
            <Queue v-if="RecordWizardStore.data.step === 'queue'"/>
            <Record v-if="RecordWizardStore.data.step === 'record'"/>
            <Check v-if="RecordWizardStore.data.step === 'check'"/>

            <!--            <div v-if="!RecordWizardStore.data.isContentVisible" class="rw-page-loading">-->
            <!--                <img class="loading" src="/img/wait.svg" alt="Loading"/>-->
            <!--            </div>-->
        </div>
    </div>

    <ModalWrapper v-model="showAlert">
        <NavGuard
            :message="`Are you sure you want to leave the Record Wizard? Your ${RecordStore.data.statusCount.stashed} stashed recordings will be lost.`"
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>
</template>
