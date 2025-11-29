<script setup>
import {computed, onMounted, ref, watch} from 'vue';
import {useRecordWizardStore} from "../stores/RecordWizardStore.js";
import {useRecordStore} from '../stores/RecordStore';
import {useQueueStore} from '../stores/QueueStore.js';
import WizardVUMeter from '../ui/WizardVUMeter.vue';
import WizardProgressBar from "../ui/WizardProgressBar.vue";
import AppTip from "../../../../components/AppTip.vue";
import PopupWindow from "../../../../components/Modals/PopupWindow.vue";

const RecordWizardStore = useRecordWizardStore();
const RecordStore = useRecordStore();
const QueueStore = useQueueStore();

const originalQueueLength = QueueStore.queue.length;

const alertLimitReached = ref(null);
const alertQueueCompleted = ref(null);

const canRecord = computed(() => {
    return RecordStore.data.statusCount.stashed + RecordStore.data.statusCount.done < 500;
});

watch(
    () => RecordStore.data.statusCount.stashed + RecordStore.data.statusCount.done,
    (recorded) => {
        if (recorded >= 500 && RecordStore.recorder) {
            RecordStore.closeRecorder();
            alertLimitReached.value?.openDialog();

        } else if (recorded < 500 && !RecordStore.recorder) {
            RecordStore.openRecorder(RecordStore.audioParams);
        }
    }
);

watch(
    () => RecordStore.data.statusCount.done,
    (uploaded) => {
        if (uploaded >= originalQueueLength) {
            alertQueueCompleted.value?.openDialog();
        }
    }
);

onMounted(() => {
    QueueStore.initSelection();
});
</script>

<template>
    <div class="window-section-head">
        <h2>Record</h2>
    </div>
    <AppTip v-if="RecordWizardStore.data.errorMessage">
        <p>{{ RecordWizardStore.data.errorMessage }}</p>
    </AppTip>

    <div class="rw-page__record mwe-rws-audio"
         :class="{ 'mwe-rws-recording': RecordWizardStore.data.isRecording }">
        <section class="rw-record-queue">
            <div
                v-for="(pronunciation, index) in QueueStore.queue"
                :key="pronunciation.id"
                :class="{
                        'selected': QueueStore.selected === index,
                        'mwe-rw-error': RecordStore.data.errors[pronunciation.id],
                        [RecordStore.data.status[pronunciation.id]]: !!RecordStore.data.status[pronunciation.id]
                    }"
                @click="QueueStore.selectItem(index)"
            >
                {{ pronunciation.term.term }}
            </div>
        </section>

        <section>
            <div class="rw-record-item-container">
                <img
                    :class="{
                            'arrow': true,
                            'disabled': QueueStore.selected - 1 < 0
                        }"
                    src="/img/reverse.svg"
                    alt="Back"
                    @click="QueueStore.moveBackward"
                />
                <div class="rw-record-item">
                    <div>
                        {{ QueueStore.queue[QueueStore.selected]?.term.term || '' }}
                    </div>
                    <div>
                        {{ QueueStore.queue[QueueStore.selected]?.translit || '' }}
                    </div>
                </div>
                <img
                    :class="{
                            'arrow': true,
                            'disabled': QueueStore.selected + 1 >= QueueStore.queue.length
                        }"
                    src="/img/play.svg"
                    alt="Forward"
                    @click="QueueStore.moveForward"
                />
            </div>

            <!--           TODO: disable it if no word is selected -->
            <div :class="`rw-actions ${RecordWizardStore.data.isUploading && 'disabled'}`">
                <div class="rw-actions-title">
                    Record
                </div>
                <div class="rw-actions-content">
                    <div class="rw-actions-bar">
                        <template
                            v-if="RecordStore.data.status[QueueStore.queue[QueueStore.selected]?.id] !== 'stashed'">
                            <template v-if="canRecord">
                                <img
                                    class="toggle-record"
                                    :src="`/img/${!RecordWizardStore.data.isRecording ? 'record' : 'stop'}.svg`"
                                    :alt="!RecordWizardStore.data.isRecording ? 'Record' : 'Stop'"
                                    @click="RecordStore.toggleRecording"
                                />
                                <WizardVUMeter id="rw-vumeter"
                                               :value="RecordStore.vumeter"
                                               :class="{ 'saturated': RecordStore.saturated, 'recording': RecordWizardStore.data.isRecording }"
                                />
                            </template>
                        </template>
                        <template v-else>
                            <img
                                class="trash"
                                src="/img/trash.svg"
                                alt="Discard"
                                @click="RecordStore.discardRecord(QueueStore.queue[QueueStore.selected]?.id)"
                            />
                            <img
                                class="audio"
                                src="/img/audio.svg"
                                alt="Listen"
                                @click="RecordStore.playRecord"
                            />
                        </template>
                    </div>

                    <div class="rw-item-counter">
                        {{ RecordStore.data.statusCount.stashed }} of {{ QueueStore.queue.length }}
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
                    Upload
                </div>
                <div class="rw-actions-content">
                    <template v-if="Object.keys(RecordStore.data.records).length !== 0">
                        <div class="rw-actions-bar">
                            <img
                                v-if="RecordStore.data.statusCount.done < Object.keys(RecordStore.data.records).length"
                                class="upload"
                                src="/img/upload.svg"
                                alt="Upload"
                                @click="RecordStore.uploadRecords"
                            />
                            <!--                    :disabled="RecordWizardStore.hasPendingRequests"-->
                            <!--                            (RecordWizardStore.data.isUploading === false || RecordWizardStore.hasPendingRequests === true)-->

                            <WizardProgressBar
                                :value="(100 * RecordStore.data.statusCount.done / Object.keys(RecordStore.data.records).length)"
                            />
                        </div>

                        <div class="rw-item-counter">
                            <span>{{ RecordStore.data.statusCount.done }}</span>
                            of
                            <span>{{ Object.keys(RecordStore.data.records).length }}</span>
                        </div>
                    </template>

                    <template v-else>
                        Record something to get started!
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
