<script setup>
import {onMounted, onUnmounted, reactive, ref, watch} from 'vue';
import {useStateStore} from "../store/StateStore.js";
import {useRecordStore} from '../store/RecordStore';
import {useListStore} from '../store/ListStore';
import WizardVUMeter from '../ui/WizardVUMeter.vue';
import LinguaRecorder from "../../../utils/LinguaRecorder.js";
import WizardProgressBar from "../ui/WizardProgressBar.vue";

const StateStore = useStateStore();
const RecordStore = useRecordStore();
const ListStore = useListStore();

let recorder;
const vumeter = ref(0);
const saturated = ref(false);

const audioParams = reactive({
    startThreshold: 0.1,
    stopThreshold: 0.05,
    saturationThreshold: 0.99,
    stopDuration: 0.3,
    marginBefore: 0.25,
    marginAfter: 0.25,
});

const showError = (error) => {
    const errorMessageAssociation = {
        AbortError: 'Technical issue with the microphone.',
        NotAllowedError: 'Microphone access was not allowed.',
        NotFoundError: 'Microphone not found.',
        NotReadableError: 'Technical issue with the microphone.',
        OverconstrainedError: 'Microphone not found.',
        SecurityError: 'Technical issue with the microphone.',
    };

    alert(errorMessageAssociation[error.name] || 'An unknown error occurred.');
};

const toggleRecord = () => {
    if (StateStore.data.isRecording) {
        cancelRecord();
    } else {
        if (RecordStore.data.pronunciations[ListStore.selected]) startRecord();
    }
};

const startRecord = () => {
    StateStore.data.isRecording = true;
    saturated.value = false;

    recorder.start();
};

const cancelRecord = () => {
    StateStore.data.isRecording = false;
    saturated.value = false;
    vumeter.value = 0;

    recorder.cancel();
};

const onDataAvailable = async (record) => {
    const currentPronunciation = RecordStore.data.pronunciations[ListStore.selected];
    if (currentPronunciation) {
        const blob = record.getBlob();

        if (blob) {
            try {
                await RecordStore.stashRecord(currentPronunciation, blob);

                if (RecordStore.data.status[currentPronunciation.id] === 'stashed') {
                    cancelRecord();
                    ListStore.moveForward();

                    if (RecordStore.data.status[RecordStore.data.pronunciations[ListStore.selected]?.id] !== 'stashed') startRecord();
                }

            } catch (error) {
                console.error('Error during stashing:', error);
                cancelRecord();
            }

        } else {
            console.error('No audio blob available to stash.');
            cancelRecord();
        }
    } else {
        console.warn('No pronunciation selected for stashing.');
    }
};

const onSaturate = () => {
    RecordStore.saturated = true;
};

onMounted(() => {
    recorder = new LinguaRecorder({
        autoStart: true,
        autoStop: true,
        startThreshold: audioParams.startThreshold,
        stopThreshold: audioParams.stopThreshold,
        saturationThreshold: audioParams.saturationThreshold,
        stopDuration: audioParams.stopDuration,
        marginBefore: audioParams.marginBefore,
        marginAfter: audioParams.marginAfter,
    });

    recorder.on('ready', () => {
        console.log('Recorder is ready!');
    });

    recorder.on('readyFail', showError);

    recorder.on('recording', (samples) => {
        vumeter.value = Math.max(...samples) * 1000;
    });

    recorder.on('saturated', onSaturate);

    recorder.on('stopped', (record) => {
        console.log('Recorder has been stopped!');

        onDataAvailable(record);
    });

    ListStore.initSelection();

    return () => {
        if (recorder) {
            recorder.close();
        }
    };
});

onUnmounted(() => {
    if (recorder) {
        recorder.off('ready');
        recorder.off('readyFail');
        recorder.off('recording');
        recorder.off('saturated');
        recorder.off('stopped');
        recorder.stop(true);
        recorder.close();
    }
});

watch(audioParams, (newParams) => {
    if (recorder) {
        recorder.setConfig(newParams);
    }
}, {deep: true});
</script>

<template>
    <div class="wizard-page-title">
        <h2>Studio</h2>
    </div>

    <div class="wizard-section-container mwe-rws-audio" :class="{ 'mwe-rws-recording': StateStore.data.isRecording }">
        <section>
            <div class="rw-queue-name">{{ RecordStore.data.queue.name }}</div>
            <ul class="mwe-rw-list">
                <li
                    v-for="(pronunciation, index) in RecordStore.data.pronunciations"
                    :key="pronunciation.id"
                    :class="{
                        'selected': ListStore.selected === index,
                        [`${RecordStore.data.status[pronunciation.id]}`]: true,
                        'mwe-rw-error': RecordStore.data.errors[pronunciation.id],
                        }"
                    @click="ListStore.selectWord(index)"
                >
                    {{ pronunciation.term }}
                </li>
            </ul>
        </section>

        <section>
            <div class="mwe-rw-core">
                <div class="mwe-rw-itembox">
                    <img
                        class="arrow"
                        src="/img/reverse.svg"
                        alt="Back"
                        @click="ListStore.moveBackward"
                    />
                    <div class="mwe-rw-item">
                        <div>
                            {{ RecordStore.data.pronunciations[ListStore.selected]?.term || '' }}
                        </div>
                        <div>
                            {{ RecordStore.data.pronunciations[ListStore.selected]?.translit || '' }}
                        </div>
                    </div>
                    <img
                        class="arrow"
                        src="/img/play.svg"
                        alt="Forward"
                        @click="ListStore.moveForward"
                    />
                </div>
            </div>

<!--           TODO: disable it if no word is selected -->
            <div :class="`rw-actions ${StateStore.data.isUploading && 'disabled'}`">
                <div class="rw-actions-title">
                    Record
                </div>
                <div class="rw-actions-content">
                    <div class="rw-actions-bar">
                        <template
                            v-if="RecordStore.data.status[RecordStore.data.pronunciations[ListStore.selected]?.id] !== 'stashed'">
                            <img
                                class="toggle-record"
                                :src="`/img/${!StateStore.data.isRecording ? 'record' : 'stop'}.svg`"
                                :alt="!StateStore.data.isRecording ? 'Record' : 'Stop'"
                                @click="toggleRecord"
                            />
                            <WizardVUMeter id="wizard-vumeter"
                                           :value="vumeter"
                                           :class="{ 'saturated': saturated, 'recording': StateStore.data.isRecording }"
                            />
                        </template>
                        <template v-else>
                            <img
                                class="trash"
                                src="/img/trash.svg"
                                alt="Discard"
                                @click="RecordStore.discardRecord(RecordStore.data.pronunciations[ListStore.selected]?.id)"
                            />
                            <img
                                class="audio"
                                src="/img/audio.svg"
                                alt="Listen"
                                @click="RecordStore.playRecord"
                            />
                        </template>
                    </div>

                    <div class="rw-actions-counter">
                        {{ RecordStore.data.statusCount.stashed }} of {{ RecordStore.data.pronunciations.length }}
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
<!--                    :disabled="StateStore.hasPendingRequests"-->
<!--                            (StateStore.data.isUploading === false || StateStore.hasPendingRequests === true)-->

                            <WizardProgressBar
                                :value="(100 * RecordStore.data.statusCount.done / Object.keys(RecordStore.data.records).length)"
                            />
                        </div>

                        <div class="rw-actions-counter">
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
</template>
