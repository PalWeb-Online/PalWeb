<script setup>
import {computed, onMounted, onUnmounted, reactive, ref, watch} from 'vue';
import {useRecordWizardStore} from "../stores/RecordWizardStore.js";
import {useRecordStore} from '../stores/RecordStore';
import {useQueueStore} from '../stores/QueueStore.js';
import WizardVUMeter from '../ui/WizardVUMeter.vue';
import WizardProgressBar from "../ui/WizardProgressBar.vue";

const RecordWizardStore = useRecordWizardStore();
const RecordStore = useRecordStore();
const QueueStore = useQueueStore();

const originalQueueLength = QueueStore.data.items.length;

const dialogLimitReached = ref(null);
const dialogQueueCompleted = ref(null);

const audioParams = reactive({
    startThreshold: 0.1,
    stopThreshold: 0.05,
    saturationThreshold: 0.99,
    stopDuration: 0.3,
    marginBefore: 0.25,
    marginAfter: 0.25,
});

onMounted(() => {
    RecordStore.openRecorder(audioParams);
    QueueStore.initSelection();
});

onUnmounted(() => {
    RecordStore.closeRecorder();
});

const canRecord = computed(() => {
    return RecordStore.data.statusCount.stashed + RecordStore.data.statusCount.done < 500;
});

watch(
    () => RecordStore.data.statusCount.stashed + RecordStore.data.statusCount.done,
    (recorded) => {
        if (recorded >= 500 && RecordStore.recorder) {
            RecordStore.closeRecorder();
            dialogLimitReached.value?.openDialog();
        } else if (recorded < 500 && !RecordStore.recorder) {
            RecordStore.openRecorder(audioParams);
        }
    }
);

watch(
    () => RecordStore.data.statusCount.done,
    (uploaded) => {
        if (uploaded >= originalQueueLength) {
            dialogQueueCompleted.value?.openDialog();
        }
    }
);

// watch(audioParams, (newParams) => {
//     if (recorder) {
//         recorder.setConfig(newParams);
//     }
// }, {deep: true});
</script>

<template>
    <div class="rw-page-title">
        <h2>Record</h2>
    </div>
    <div v-if="RecordWizardStore.data.errorMessage" class="tip error">
        <div class="material-symbols-rounded">info</div>
        <div class="tip-content">
            <p>{{ RecordWizardStore.data.errorMessage }}</p>
        </div>
    </div>

    <div class="rw-page__record mwe-rws-audio" :class="{ 'mwe-rws-recording': RecordWizardStore.data.isRecording }">
        <section>
            <div class="rw-record-queue">
                <div
                    v-for="(pronunciation, index) in QueueStore.data.items"
                    :key="pronunciation.id"
                    :class="{
                        'selected': QueueStore.selected === index,
                        'mwe-rw-error': RecordStore.data.errors[pronunciation.id],
                        [RecordStore.data.status[pronunciation.id]]: !!RecordStore.data.status[pronunciation.id]
                    }"
                    @click="QueueStore.selectItem(index)"
                >
                    {{ pronunciation.term }}
                </div>
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
                        {{ QueueStore.data.items[QueueStore.selected]?.term || '' }}
                    </div>
                    <div>
                        {{ QueueStore.data.items[QueueStore.selected]?.translit || '' }}
                    </div>
                </div>
                <img
                    :class="{
                            'arrow': true,
                            'disabled': QueueStore.selected + 1 >= QueueStore.data.items.length
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
                            v-if="RecordStore.data.status[QueueStore.data.items[QueueStore.selected]?.id] !== 'stashed'">
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
                                @click="RecordStore.discardRecord(QueueStore.data.items[QueueStore.selected]?.id)"
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
                        {{ RecordStore.data.statusCount.stashed }} of {{ QueueStore.data.items.length }}
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

    <AppDialog ref="dialogLimitReached" title="Limit Reached" size="large">
        <template #content>
            <p>Wow! You’ve recorded 500 Audios in one session! In order to guarantee good performance from the Record
                Wizard, please upload any recordings you still have stashed & refresh the page before recording anything
                more.</p>
        </template>
    </AppDialog>
    <AppDialog ref="dialogQueueCompleted" title="Queue Completed" size="large">
        <template #content>
            <p>Wonderful! You've uploaded all the items in your Queue. Proceed to the <b>Check</b> step to review your
                uploads, or return to the <b>Queue</b> step to load in another set of items.</p>
        </template>
    </AppDialog>
</template>
