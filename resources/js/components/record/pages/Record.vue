<script setup>
import {onMounted, reactive, ref, watch} from 'vue';
import {useRecordStore} from '../store/RecordStore';
import {useListStore} from '../store/ListStore';
import WizardButton from '../ui/WizardButton.vue';
import WizardVUMeter from '../ui/WizardVUMeter.vue';

const {
    words,
    selected,
    selectedArray,
    initSelection,
    moveForward,
    moveBackward,
} = useListStore(); // List-related logic

const RecordStore = useRecordStore();

// Reactive states
const metadata = reactive(RecordStore.data.metadata); // Language, media type, etc.
const status = reactive(RecordStore.data.status); // Recording status
const statusCount = reactive(RecordStore.data.statusCount); // Track counts for errors, stashing, etc.
const isRecording = ref(false);
const saturated = ref(false);
const vumeter = ref(0);
const countdown = ref(0);

const audioParams = reactive({
    startThreshold: 0.1,
    stopThreshold: 0.05,
    saturationThreshold: 0.99,
    stopDuration: 0.3,
    marginBefore: 0.25,
    marginAfter: 0.25,
});

let recorder = null; // Placeholder for the recording object

// Methods for recording
const toggleRecord = () => {
    if (isRecording.value) {
        cancelRecord();
    } else {
        startRecord();
    }
};

const startRecord = () => {
    if (!isRecording.value) {
        console.log('Recording started');
        isRecording.value = true;
        saturated.value = false;
        // Trigger recording start on your chosen recorder
        // Example: recorder.start();
    }
};

const cancelRecord = () => {
    if (isRecording.value) {
        console.log('Recording canceled');
        isRecording.value = false;
        saturated.value = false;
        vumeter.value = 0;
        countdown.value = 0;
        // Trigger cancel on your chosen recorder
        // Example: recorder.cancel();
    }
};

const removeRecord = (word) => {
    console.log('Removing record for', word);
    RecordStore.clearRecord(word); // Reset the record using store
};

const runCountdown = () => {
    if (isRecording.value) {
        countdown.value--;
        if (countdown.value > 0) {
            setTimeout(runCountdown, 1000);
        } else {
            console.log('Start recording after countdown');
        }
    }
};

const playRecord = (word) => {
    if (status[word] === 'stashed') {
        // Play the stashed version of the record
        console.log(`Playing record for word: ${word}`);
        const audio = new Audio(RecordStore.getMediaUrl(word)); // Use appropriate URL retrieval method
        audio.play();
    }
};

const onDataAvailable = (samples) => {
    let amplitudeMax = Math.max(...samples.map(Math.abs));
    // Update VU meter value
    vumeter.value = Math.floor((-10 * amplitudeMax ** 2) + 25 * amplitudeMax);
};

const onSaturate = () => {
    saturated.value = true;
};

// Watchers
watch(audioParams, () => {
    cancelRecord();
    // Update recorder with new audio parameters if needed
}, {deep: true});

// Lifecycle hook: onMounted
onMounted(() => {

    recorder.on('dataAvailable', onDataAvailable);
    recorder.on('saturated', onSaturate);

    // Handle keyboard shortcuts
    const shortcutsHandler = (event) => {
        if (event.target.tagName === 'INPUT' || event.target.tagName === 'BUTTON') return;

        switch (event.key) {
            case 'ArrowLeft':
            case 'ArrowUp':
                moveBackward();
                break;
            case 'ArrowRight':
            case 'ArrowDown':
                moveForward();
                break;
            case 'Delete':
            case 'Backspace':
                removeRecord(RecordStore.data.pronunciations[RecordStore.selected]); // Use the store for word management
                break;
            case ' ':
                toggleRecord();
                break;
            case 'p':
                playRecord(RecordStore.data.pronunciations[RecordStore.selected]); // Use the store for playing records
                break;
            default:
                return;
        }
        event.preventDefault();
    };

    document.addEventListener('keydown', shortcutsHandler);

    // Cleanup on unmount
    return () => {
        document.removeEventListener('keydown', shortcutsHandler);
        if (recorder) recorder.destroy(); // Destroy recorder instance if applicable
    };
});
</script>

<template>
    <div class="wizard-page-title">
        <h2>Record</h2>
    </div>

    <!--    <WizardPopup>-->
    <!--        <template #trigger>-->
    <!--            <i id="mwe-rws-settings" class="mwe-rw-topicon"></i>-->
    <!--        </template>-->
    <!--        <template #content>-->
    <!--            <div id="mwe-rws-settings-audio">-->
    <!--                <h4>Audio Recording Settings</h4>-->
    <!--                <label>Start Threshold</label>-->
    <!--                <WizardNumber v-model="audioParams.startThreshold" :max="100" :min="0" :percentage="true"/>-->
    <!--                <label>Stop Threshold</label>-->
    <!--                <WizardNumber v-model="audioParams.stopThreshold" :max="100" :min="0" :percentage="true"/>-->
    <!--                <label>Saturation Threshold</label>-->
    <!--                <WizardNumber v-model="audioParams.saturationThreshold" :max="100" :min="0" :percentage="true"/>-->
    <!--                <label>Stop Duration</label>-->
    <!--                <WizardNumber v-model="audioParams.stopDuration" :max="5" :min="0" :step="0.05"/>-->
    <!--                <label>Margin Before</label>-->
    <!--                <WizardNumber v-model="audioParams.marginBefore" :max="5" :min="0" :step="0.05"/>-->
    <!--                <label>Margin After</label>-->
    <!--                <WizardNumber v-model="audioParams.marginAfter" :max="5" :min="0" :step="0.05"/>-->
    <!--            </div>-->
    <!--        </template>-->
    <!--    </WizardPopup>-->

    <!--    <WizardDialog name="shortcuts" size="large">-->
    <!--        <template #trigger>-->
    <!--            <i id="mwe-rws-shortcuts" class="mwe-rw-topicon"></i>-->
    <!--        </template>-->
    <!--        <template #content>-->
    <!--            <div id="mwe-rws-shortcuts-content">-->
    <!--                <h4>Shortcuts</h4>-->
    <!--                <section>-->
    <!--                    <div>-->
    <!--                        <kbd class="mwe-rw-key">←</kbd>-->
    <!--                        <div>Previous Word</div>-->
    <!--                    </div>-->
    <!--                    <div>-->
    <!--                        <kbd class="mwe-rw-key">→</kbd>-->
    <!--                        <div>Next Word</div>-->
    <!--                    </div>-->
    <!--                    <div>-->
    <!--                        <kbd class="mwe-rw-key">del</kbd>-->
    <!--                        <div>Delete Recording</div>-->
    <!--                    </div>-->
    <!--                    <div>-->
    <!--                        <kbd class="mwe-rw-key">P</kbd>-->
    <!--                        <div>Listen to Recording</div>-->
    <!--                    </div>-->
    <!--                    <div>-->
    <!--                        <kbd class="mwe-rw-key mwe-rw-key-long">space</kbd>-->
    <!--                        <div>Toggle Recording</div>-->
    <!--                    </div>-->
    <!--                </section>-->
    <!--            </div>-->
    <!--        </template>-->
    <!--    </WizardDialog>-->

    <div class="wizard-section-container mwe-rws-audio" :class="isRecording ? ' mwe-rws-recording' : ''">
        <section>
            <ul class="mwe-rw-list">
                <li v-for="(element, index) in RecordStore.data.pronunciations" :key="index" @click="playRecord(word)">
                    {{ element.term }}
                </li>
            </ul>
        </section>

        <section>
            <div class="mwe-rw-core">
                <div class="mwe-rw-itembox">
                    <div class="mwe-rw-item">
                        <div>
                            {{ RecordStore.data.pronunciations[0].term }}
                        </div>
                        <div>
                            {{ RecordStore.data.pronunciations[0].translit }}
                        </div>
                    </div>
                </div>
                <WizardButton id="mwe-rws-skip" :framed="false" icon="next" label="Skip" @click="moveForward"/>
            </div>

            <div class="mwe-rw-actions">
                <WizardButton
                    id="mwe-rws-record"
                    :disabled="metadata.media === 'video' && videoStream === null"
                    flags="primary progressive"
                    icon="ll-record"
                    @click="toggleRecord"
                />
                <WizardVUMeter id="mwe-rws-vumeter" :class="{ 'mwe-rws-saturated': saturated }" :value="vumeter"/>

                <div id="mwe-rws-record-indicator">
                    <div v-if="countdown === 0 && !saturated" id="mwe-rws-recording"></div>
                    <div v-if="saturated">SAT</div>
                    <div v-if="countdown > 0">{{ countdown }}</div>
                </div>

                <div id="mwe-rws-counter" class="mwe-rw-counter">
                    <span>{{ statusCount.stashed }}</span> / <span>{{
                        RecordStore.data.pronunciations.length
                    }}</span>
                    <span class="mwe-rw-othercounter">
                            <span v-if="statusCount.error > 0" class="mwe-rw-errorcounter">
                                <i></i>
                                <span>{{ statusCount.error }}</span>
                            </span>
                            <span v-if="statusCount.stashing > 0" class="mwe-rw-progresscounter">
                                <i></i>
                                <span>{{ statusCount.stashing }}</span>
                            </span>
                        </span>
                </div>
            </div>
        </section>
    </div>
</template>
