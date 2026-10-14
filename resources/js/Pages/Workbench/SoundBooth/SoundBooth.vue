<script setup>
import {computed, onBeforeUnmount, onMounted} from "vue";
import {merge} from "lodash";
import {route} from "ziggy-js";
import Layout from "../../../Shared/Layout.vue";
import {useSoundBoothStore} from "./stores/SoundBoothStore.js";
import {useQueueStore} from "./stores/QueueStore.js";
import {useRecordStore} from "./stores/RecordStore.js";
import {useNavGuard} from "../../../composables/NavGuard.js";
import Speaker from "./pages/Speaker.vue";
import Queue from "./pages/Queue.vue";
import Record from "./pages/Record.vue";
import Check from "./pages/Check.vue";
import NavGuard from "../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";

defineOptions({
    layout: Layout
});

const SoundBoothStore = useSoundBoothStore();
const QueueStore = useQueueStore();
const RecordStore = useRecordStore();

const hasNavigationGuard = computed(() => {
    return RecordStore.data.statusCount.stashed > 0;
});

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

onMounted(async () => {
    const {data} = await axios.get(route('sound-booth.speaker'));

    if (data.speaker) {
        merge(SoundBoothStore.speaker, data.speaker);
    }
});

onBeforeUnmount(() => {
    SoundBoothStore.data.step = 'speaker';
    QueueStore.queue = [];

    if (RecordStore.recorder) {
        RecordStore.closeRecorder();
    }
});
</script>

<template>
    <div id="app-body">
        <div class="rw-container window-container">
            <div class="window-section-head">
                <h1>{{ $t('pages.sound-booth.title') }}</h1>
            </div>
            <div class="window-page-nav">
                <button class="material-symbols-rounded" :disabled="SoundBoothStore.backDisabled"
                        @click="SoundBoothStore.back">arrow_back
                </button>
                <div class="material-symbols-rounded"
                     :class="{ active: SoundBoothStore.data.step === 'speaker', disabled: SoundBoothStore.data.step !== 'speaker' }">
                    voice_selection
                </div>
                <div class="material-symbols-rounded" :class="{ active: SoundBoothStore.data.step === 'queue' }">
                    list
                </div>
                <div class="material-symbols-rounded" :class="{ active: SoundBoothStore.data.step === 'record' }">
                    mic
                </div>
                <div class="material-symbols-rounded" :class="{ active: SoundBoothStore.data.step === 'check' }">
                    checklist
                </div>
                <button class="material-symbols-rounded" :disabled="SoundBoothStore.nextDisabled"
                        @click="SoundBoothStore.next">arrow_forward
                </button>
            </div>
            <Speaker v-if="SoundBoothStore.data.step === 'speaker'"/>
            <Queue v-if="SoundBoothStore.data.step === 'queue'"/>
            <Record v-if="SoundBoothStore.data.step === 'record'"/>
            <Check v-if="SoundBoothStore.data.step === 'check'"/>

            <!--            <div v-if="!SoundBoothStore.data.isContentVisible" class="rw-page-loading">-->
            <!--                <img class="loading" src="/img/wait.svg" alt="Loading"/>-->
            <!--            </div>-->
        </div>
    </div>

    <ModalWrapper v-model="showAlert">
        <NavGuard
            :message="`Are you sure you want to leave the Sound Booth? Your ${RecordStore.data.statusCount.stashed} stashed recordings will be lost.`"
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>
</template>
