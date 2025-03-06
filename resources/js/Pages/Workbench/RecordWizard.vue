<script setup>
import {onBeforeUnmount} from "vue";
import {useStateStore} from '../../components/record/stores/StateStore.js';
import {useQueueStore} from "../../components/record/stores/QueueStore.js";
import {useRecordStore} from "../../components/record/stores/RecordStore.js";
import {useNavGuard} from "../../composables/NavGuard.js";
import Speaker from '../../components/record/pages/Speaker.vue';
import Queue from '../../components/record/pages/Queue.vue';
import Record from '../../components/record/pages/Record.vue';
import Check from '../../components/record/pages/Check.vue';
import Layout from "../../Shared/Layout.vue";
import AppAlert from "../../components/AppAlert.vue";

const StateStore = useStateStore();
const QueueStore = useQueueStore();
const RecordStore = useRecordStore();

const { showAlert, handleConfirm, handleCancel } = useNavGuard(StateStore.hasNavigationGuard);

onBeforeUnmount(() => {
    StateStore.data.step = 'speaker';
    QueueStore.data.items = [];
});

defineOptions({
    layout: Layout
});
</script>

<template>
    <Head title="Record Wizard"/>
    <div id="app-head">
        <h1>Record Wizard</h1>

        <div id="app-nav">
            <img :class="StateStore.backDisabled ? 'disabled' : ''" alt="Back" src="/img/finger-back.svg"
                 @click="StateStore.back"/>
            <div class="app-nav-steps">
                <div v-if="StateStore.data.step === 'speaker'" :class="{ active: StateStore.data.step === 'speaker' }">
                    Speaker
                </div>
                <template v-else>
                    <div :class="{ active: StateStore.data.step === 'queue' }">Queue</div>
                    <div :class="{ active: StateStore.data.step === 'record' }">Record</div>
                    <div :class="{ active: StateStore.data.step === 'check' }">Check</div>
                </template>
            </div>
            <img :class="StateStore.nextDisabled ? 'disabled' : ''" alt="Next" src="/img/finger-next.svg"
                 @click="StateStore.next"/>
        </div>
    </div>

    <div id="app-body">
        <div class="rw-container">
            <div class="rw-page-content">
                <Speaker v-if="StateStore.data.step === 'speaker'"/>
                <Queue v-if="StateStore.data.step === 'queue'"/>
                <Record v-if="StateStore.data.step === 'record'"/>
                <Check v-if="StateStore.data.step === 'check'"/>
            </div>
            <!--            <div v-if="!StateStore.data.isContentVisible" class="rw-page-loading">-->
            <!--                <img class="loading" src="/img/wait.svg" alt="Loading"/>-->
            <!--            </div>-->
        </div>
    </div>

    <AppAlert
        v-if="showAlert"
        :message="`Are you sure you want to leave the Record Wizard? Your ${RecordStore.data.statusCount.stashed} stashed recordings will be lost.`"
        @confirm="handleConfirm"
        @cancel="handleCancel"
    />
</template>
