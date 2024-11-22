<script setup>
import {onMounted, onUnmounted, reactive, ref, watch} from 'vue';
import {useRecordStore} from '../store/RecordStore';
import {useListStore} from '../store/ListStore';
import WizardButton from '../ui/WizardButton.vue';
import WizardVUMeter from '../ui/WizardVUMeter.vue';
import LinguaRecorder from "../../../utils/LinguaRecorder.js";

const RecordStore = useRecordStore();
const ListStore = useListStore();

const isRecording = ref(false);
const saturated = ref(false);
const vumeter = ref(0);
const countdown = ref(0);
let recorder;

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
    if (isRecording.value) {
        cancelRecord();
    } else {
        const currentPronunciation = ListStore.pronunciations[ListStore.selected];
        if (currentPronunciation) {
            console.log(`Starting recording for: ${currentPronunciation.term}`);
            startRecord();
        } else {
            alert('No word selected to record.');
        }
    }
};

const startRecord = () => {
    console.log('Recording started');
    isRecording.value = true;
    saturated.value = false;

    recorder.start();
};

const cancelRecord = () => {
    console.log('Recording canceled');
    isRecording.value = false;
    saturated.value = false;
    vumeter.value = 0;
    countdown.value = 0;
    recorder.cancel();
};

const playRecord = () => {
    const currentPronunciation = ListStore.pronunciations[ListStore.selected];
    if (currentPronunciation && RecordStore.data.status[currentPronunciation.id] === 'stashed') {
        const record = RecordStore.data.records[currentPronunciation.id];
        if (record && record.url) {
            console.log(`Playing record for: ${currentPronunciation.term}`);
            const audio = new Audio(record.url);
            audio.play();
        } else {
            console.error('No URL found for stashed recording.');
            alert('No recording available for this word.');
        }
    } else {
        alert('No recording available for this word.');
    }
};

const selectWord = (index) => {
    ListStore.selectWord(index);

    if (RecordStore.data.status[ListStore.pronunciations[index].id] === 'stashed') {
        playRecord();
    }
};

const onDataAvailable = async (record) => {
    const currentPronunciation = ListStore.pronunciations[ListStore.selected];
    if (currentPronunciation) {
        console.log(`Stashing record for: ${currentPronunciation.term}`);
        const blob = record.getBlob();

        if (blob) {
            try {
                await RecordStore.doStash(currentPronunciation.id, blob);

                if (RecordStore.data.status[currentPronunciation.id] === 'stashed') {
                    recorder.stop();
                    ListStore.moveForward();
                    startRecord();
                }

            } catch (error) {
                console.error('Error during stashing:', error);
                recorder.cancel();
            }

        } else {
            console.error('No audio blob available to stash.');
            recorder.cancel();
        }

    } else {
        console.warn('No pronunciation selected for stashing.');
    }
};

const onSaturate = () => {
    saturated.value = true;
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
        console.log(vumeter.value);
    });

    recorder.on('saturated', onSaturate);

    recorder.on('stopped', (record) => {
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
        <h2>Record</h2>
    </div>

    <div class="wizard-section-container mwe-rws-audio" :class="{ 'mwe-rws-recording': isRecording }">
        <section>
            <ul class="mwe-rw-list">
                <li
                    v-for="(element, index) in ListStore.pronunciations"
                    :key="index"
                    :class="{
                        'selected': ListStore.selected === index,
                        'stashed': RecordStore.data.status[element.id] === 'stashed',
                        }"
                    @click="selectWord(index)"
                >
                    {{ element.term }}
                </li>
            </ul>
        </section>

        <section>
            <div class="mwe-rw-core">
                <div class="mwe-rw-itembox">
                    <div class="mwe-rw-item">
                        <div>
                            {{ ListStore.pronunciations[ListStore.selected]?.term || 'No term selected' }}
                        </div>
                        <div>
                            {{ ListStore.pronunciations[ListStore.selected]?.translit || '' }}
                        </div>
                    </div>
                </div>
                <WizardButton id="mwe-rws-skip" :framed="false" icon="next" label="Skip"
                              @click="ListStore.moveForward"/>
            </div>

            <div class="mwe-rw-actions">
                <img
                    class="toggle-record"
                    :src="`/img/${!isRecording ? 'record' : 'stop'}.svg`"
                    :alt="!isRecording ? 'Record' : 'Stop'"
                    @click="toggleRecord"
                />

                <WizardVUMeter id="wizard-vumeter"
                               :value="vumeter"
                               :class="{ 'saturated': saturated, 'recording': isRecording }"
                />

                <div class="wizard-record-counter">
                    {{ RecordStore.data.statusCount.stashed }} of {{ ListStore.pronunciations.length }}

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
            </div>
        </section>
    </div>
</template>
