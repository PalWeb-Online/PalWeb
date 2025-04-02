<script setup>
import {computed, onBeforeUnmount, onMounted} from "vue";
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

onMounted(() => {
    RecordWizardStore.speaker = merge(RecordWizardStore.speaker, props.speaker);
});

onBeforeUnmount(() => {
    RecordWizardStore.data.step = 'speaker';
    QueueStore.queue = [];
});

</script>

<template>
    <Head title="Record Wizard"/>
    <div id="app-head">
        <h1>Record Wizard</h1>

        <div id="app-nav">
            <img :class="RecordWizardStore.backDisabled ? 'disabled' : ''" alt="Back" src="/img/finger-back.svg"
                 @click="RecordWizardStore.back"/>
            <div class="app-nav-steps">
                <div v-if="RecordWizardStore.data.step === 'speaker'"
                     :class="{ active: RecordWizardStore.data.step === 'speaker' }">
                    Speaker
                </div>
                <template v-else>
                    <div :class="{ active: RecordWizardStore.data.step === 'queue' }">Queue</div>
                    <div :class="{ active: RecordWizardStore.data.step === 'record' }">Record</div>
                    <div :class="{ active: RecordWizardStore.data.step === 'check' }">Check</div>
                </template>
            </div>
            <img :class="RecordWizardStore.nextDisabled ? 'disabled' : ''" alt="Next" src="/img/finger-next.svg"
                 @click="RecordWizardStore.next"/>
        </div>
    </div>

    <div id="app-body">
        <div class="rw-container">
            <div class="rw-page-content">
                <Speaker v-if="RecordWizardStore.data.step === 'speaker'"/>
                <Queue v-if="RecordWizardStore.data.step === 'queue'"/>
                <Record v-if="RecordWizardStore.data.step === 'record'"/>
                <Check v-if="RecordWizardStore.data.step === 'check'"/>
            </div>
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
