<script setup>
import {useStateStore} from './stores/StateStore';
import {useRecordStore} from "./stores/RecordStore.js";
import Speaker from './pages/Speaker.vue';
import Queue from './pages/Queue.vue';
import Record from './pages/Record.vue';
import Check from './pages/Check.vue';

const StateStore = useStateStore();
const RecordStore = useRecordStore();

const exit = async () => {
    if (RecordStore.data.statusCount.stashed > 0) {
        if (confirm(`Are you sure you want to leave the wizard? Your ${RecordStore.data.statusCount.stashed} stashed recordings will be lost.`)) {
            window.location.href = '/dashboard/workbench';
        }
    } else {
        window.location.href = '/dashboard/workbench';
    }
};
</script>

<template>
    <div id="app-head">
        <button @click="exit">Exit to Workbench</button>
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
</template>
