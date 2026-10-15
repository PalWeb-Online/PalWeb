<script setup>
import {computed, onMounted, ref, watch} from 'vue';
import {useSoundBoothSession} from "../useSoundBoothSession.js";
import WizardVUMeter from '../ui/WizardVUMeter.vue';
import WizardProgressBar from "../ui/WizardProgressBar.vue";
import AppTip from "../../../../components/AppTip.vue";
import PopupWindow from "../../../../components/Modals/PopupWindow.vue";

const session = useSoundBoothSession();

const originalQueueLength = session.queue.queue.length;

const alertLimitReached = ref(null);
const alertQueueCompleted = ref(null);

const canRecord = computed(() => {
    return session.record.data.statusCount.stashed + session.record.data.statusCount.done < 500;
});

watch(
    () => session.record.data.statusCount.stashed + session.record.data.statusCount.done,
    (recorded) => {
        if (recorded >= 500 && session.record.recorder) {
            session.record.closeRecorder();
            alertLimitReached.value?.openDialog();

        } else if (recorded < 500 && !session.record.recorder) {
            session.record.openRecorder(session.record.audioParams);
        }
    }
);

watch(
    () => session.record.data.statusCount.done,
    (uploaded) => {
        if (uploaded >= originalQueueLength) {
            alertQueueCompleted.value?.openDialog();
        }
    }
);

onMounted(() => {
    session.queue.initSelection();
});
</script>

<template>
    <div class="window-section-head">
        <h2>{{ $t('sound-booth.record.record') }}</h2>
    </div>
    <AppTip v-if="session.data.errorMessage">
        <p>{{ session.data.errorMessage }}</p>
    </AppTip>

    <div class="rw-page__record mwe-rws-audio"
         :class="{ 'mwe-rws-recording': session.data.isRecording }">
        <section class="rw-record-queue">
            <div
                v-for="(pronunciation, index) in session.queue.queue"
                :key="pronunciation.id"
                :class="{
                        'selected': session.queue.selected === index,
                        'mwe-rw-error': session.record.data.errors[pronunciation.id],
                        [session.record.data.status[pronunciation.id]]: !!session.record.data.status[pronunciation.id]
                    }"
                @click="session.queue.selectItem(index)"
            >
                {{ pronunciation.term.term }}
            </div>
        </section>

        <section>
            <div class="rw-record-item-container">
                <img
                    :class="{
                            'arrow': true,
                            'disabled': session.queue.selected - 1 < 0
                        }"
                    src="/img/reverse.svg"
                    alt="Back"
                    @click="session.queue.moveBackward"
                />
                <div class="rw-record-item">
                    <div>
                        {{ session.queue.queue[session.queue.selected]?.term.term || '' }}
                    </div>
                    <div>
                        {{ session.queue.queue[session.queue.selected]?.translit || '' }}
                    </div>
                </div>
                <img
                    :class="{
                            'arrow': true,
                            'disabled': session.queue.selected + 1 >= session.queue.queue.length
                        }"
                    src="/img/play.svg"
                    alt="Forward"
                    @click="session.queue.moveForward"
                />
            </div>

            <!--           TODO: disable it if no word is selected -->
            <div :class="`rw-actions ${session.data.isUploading && 'disabled'}`">
                <div class="rw-actions-title">
                    {{ $t('sound-booth.record.record') }}
                </div>
                <div class="rw-actions-content">
                    <div class="rw-actions-bar">
                        <template
                            v-if="session.record.data.status[session.queue.queue[session.queue.selected]?.id] !== 'stashed'">
                            <template v-if="canRecord">
                                <img
                                    class="toggle-record"
                                    :src="`/img/${!session.data.isRecording ? 'record' : 'stop'}.svg`"
                                    :alt="!session.data.isRecording ? 'Record' : 'Stop'"
                                    @click="session.record.toggleRecording"
                                />
                                <WizardVUMeter id="rw-vumeter"
                                               :value="session.record.vumeter"
                                               :class="{ 'saturated': session.record.saturated, 'recording': session.data.isRecording }"
                                />
                            </template>
                        </template>
                        <template v-else>
                            <img
                                class="trash"
                                src="/img/trash.svg"
                                alt="Discard"
                                @click="session.record.discardRecord(session.queue.queue[session.queue.selected]?.id)"
                            />
                            <img
                                class="audio"
                                src="/img/audio.svg"
                                alt="Listen"
                                @click="session.record.playRecord"
                            />
                        </template>
                    </div>

                    <div class="rw-item-counter">
                        {{ session.record.data.statusCount.stashed }} / {{ session.queue.queue.length }}
                    </div>
                </div>

                <!--                    <span class="mwe-rw-othercounter">-->
                <!--                        <span-->
                <!--                            v-if="RecordStore.data.statusCount.error > 0"-->
                <!--                            class="mwe-rw-errorcounter"-->
                <!--                        >-->
                <!--                            <i></i>-->
                <!--                            <span>{{ RecordStore.data.statusCount.error }}</span>-->
                <!--                        </span>-->
                <!--                        <span-->
                <!--                            v-if="RecordStore.data.statusCount.stashing > 0"-->
                <!--                            class="mwe-rw-progresscounter"-->
                <!--                        >-->
                <!--                            <i></i>-->
                <!--                            <span>{{ RecordStore.data.statusCount.stashing }}</span>-->
                <!--                        </span>-->
                <!--                    </span>-->
            </div>

            <div class="rw-actions">
                <div class="rw-actions-title">
                    {{ $t('sound-booth.record.upload') }}
                </div>
                <div class="rw-actions-content">
                    <template v-if="Object.keys(session.record.data.records).length !== 0">
                        <div class="rw-actions-bar">
                            <img
                                v-if="session.record.data.statusCount.done < Object.keys(session.record.data.records).length"
                                class="upload"
                                src="/img/upload.svg"
                                alt="Upload"
                                @click="session.record.uploadRecords"
                            />
                            <!--                    :disabled="SoundBoothStore.hasPendingRequests"-->
                            <!--                            (SoundBoothStore.data.isUploading === false || SoundBoothStore.hasPendingRequests === true)-->

                            <WizardProgressBar
                                :value="(100 * session.record.data.statusCount.done / Object.keys(session.record.data.records).length)"
                            />
                        </div>

                        <div class="rw-item-counter">
                            <span>{{ session.record.data.statusCount.done }}</span>
                            of
                            <span>{{ Object.keys(session.record.data.records).length }}</span>
                        </div>
                    </template>

                    <template v-else>
                        {{ $t('sound-booth.record.prompt-start') }}
                    </template>

                    <!--                    <span class="mwe-rw-othercounter">-->
                    <!--              <span class="mwe-rw-errorcounter" v-if="RecordStore.data.statusCount.error > 0">-->
                    <!--                <i></i>-->
                    <!--                <span v-html="RecordStore.data.statusCount.error"></span>-->
                    <!--              </span>-->
                    <!--              <span class="mwe-rw-progresscounter"-->
                    <!--                    v-if="RecordStore.data.statusCount.uploading + RecordStore.data.statusCount.finalizing > 0">-->
                    <!--                <i></i>-->
                    <!--                <span v-html="RecordStore.data.statusCount.uploading + RecordStore.data.statusCount.finalizing"></span>-->
                    <!--              </span>-->
                    <!--            </span>-->
                </div>
            </div>
        </section>
    </div>

    <PopupWindow trigger="auto" type="alert" ref="alertLimitReached" title="Limit Reached">
        <p>Wow! You’ve recorded 500 Audios in one session! In order to guarantee good performance from the Record
            Wizard, please upload any recordings you still have stashed & refresh the page before recording anything
            more.</p>
    </PopupWindow>
    <PopupWindow trigger="auto" type="alert" ref="alertQueueCompleted" title="Queue Completed">
        <p>Wonderful! You've uploaded all the items in your Queue. Proceed to the <b>Check</b> step to review your
            uploads, or return to the <b>Queue</b> step to load in another set of items.</p>
    </PopupWindow>
</template>
