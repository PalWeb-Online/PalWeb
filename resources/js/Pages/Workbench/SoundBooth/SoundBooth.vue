<script setup>
import {computed, onBeforeUnmount, onMounted} from "vue";
import {merge} from "lodash";
import {route} from "ziggy-js";
import Layout from "../../../Shared/Layout.vue";
import {createSoundBoothSession, provideSoundBoothSession} from "./useSoundBoothSession.js";
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

const session = provideSoundBoothSession(createSoundBoothSession());

const hasNavigationGuard = computed(() => {
    return session.record.data.statusCount.stashed > 0;
});

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

onMounted(async () => {
    const {data} = await axios.get(route('sound-booth.speaker'));

    if (data.speaker) {
        merge(session.speaker, data.speaker);
    }
});

onBeforeUnmount(() => {
    session.data.step = 'speaker';
    session.queue.queue.splice(0, session.queue.queue.length);

    if (session.record.recorder) {
        session.record.closeRecorder();
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
                <button class="material-symbols-rounded" :disabled="session.backDisabled"
                        @click="session.back">arrow_back
                </button>
                <div class="material-symbols-rounded"
                     :class="{ active: session.data.step === 'speaker', disabled: session.data.step !== 'speaker' }">
                    voice_selection
                </div>
                <div class="material-symbols-rounded" :class="{ active: session.data.step === 'queue' }">
                    list
                </div>
                <div class="material-symbols-rounded" :class="{ active: session.data.step === 'record' }">
                    mic
                </div>
                <div class="material-symbols-rounded" :class="{ active: session.data.step === 'check' }">
                    checklist
                </div>
                <button class="material-symbols-rounded" :disabled="session.nextDisabled"
                        @click="session.next">arrow_forward
                </button>
            </div>
            <Speaker v-if="session.data.step === 'speaker'"/>
            <Queue v-if="session.data.step === 'queue'"/>
            <Record v-if="session.data.step === 'record'"/>
            <Check v-if="session.data.step === 'check'"/>

            <!--            <div v-if="!session.data.isContentVisible" class="rw-page-loading">-->
            <!--                <img class="loading" src="/img/wait.svg" alt="Loading"/>-->
            <!--            </div>-->
        </div>
    </div>

    <ModalWrapper v-model="showAlert">
        <NavGuard
            :message="`Are you sure you want to leave the Sound Booth? Your ${session.record.data.statusCount.stashed} stashed recordings will be lost.`"
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>
</template>
