<script setup>
import {computed, onBeforeUnmount, onMounted} from "vue";
import {merge} from "lodash";
import {createSoundBoothSession, provideSoundBoothSession} from "./useSoundBoothSession.js";
import {useNavGuard} from "../../../composables/NavGuard.js";
import Speaker from "./pages/Speaker.vue";
import Queue from "./pages/Queue.vue";
import Record from "./pages/Record.vue";
import Check from "./pages/Check.vue";
import NavGuard from "../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";
import {useUserStore} from '../../../stores/UserStore.js';

const UserStore = useUserStore();

const session = provideSoundBoothSession(createSoundBoothSession());

const hasNavigationGuard = computed(() => {
    return session.recordings.statusCount.stashed > 0;
});

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

onMounted(async () => {
    const {data} = await axios.get(`/api/speakers/${UserStore.user?.id}/edit`);

    if (data.speaker) {
        merge(session.speaker, data.speaker);
        await session.clearStash();
    }
});

onBeforeUnmount(() => {
    session.state.step = 'speaker';
    session.queue.splice(0, session.queue.length);

    if (session.recorder) {
        session.closeRecorder();
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
                <button class="material-symbols-rounded" :disabled="session.prevDisabled"
                        @click="session.prevPage">arrow_back
                </button>
                <div class="material-symbols-rounded"
                     :class="{ active: session.state.step === 'speaker', disabled: session.state.step !== 'speaker' }">
                    voice_selection
                </div>
                <div class="material-symbols-rounded" :class="{ active: session.state.step === 'queue' }">
                    list
                </div>
                <div class="material-symbols-rounded" :class="{ active: session.state.step === 'record' }">
                    mic
                </div>
                <div class="material-symbols-rounded" :class="{ active: session.state.step === 'check' }">
                    checklist
                </div>
                <button class="material-symbols-rounded" :disabled="session.nextDisabled"
                        @click="session.nextPage">arrow_forward
                </button>
            </div>
            <Speaker v-if="session.state.step === 'speaker'"/>
            <Queue v-if="session.state.step === 'queue'"/>
            <Record v-if="session.state.step === 'record'"/>
            <Check v-if="session.state.step === 'check'"/>
        </div>
    </div>

    <ModalWrapper v-model="showAlert">
        <NavGuard
            :message="$t('sound-booth.notifications.leave-confirm', { count: session.recordings.statusCount.stashed })"
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>
</template>
