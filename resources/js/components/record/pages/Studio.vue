<script setup>
import {onMounted, reactive, ref, watch} from 'vue';
import useRecordStore from '../store/useRecordStore';
import useStepStore from '../store/useStepStore';
import useListStore from '../store/useListStore';
import WizardNumber from '../ui/WizardNumberInput.vue';
import WizardButton from '../ui/WizardButton.vue';
import WizardPopup from '../ui/WizardPopup.vue';
import WizardDialog from '../ui/WizardDialog.vue';
import WizardSelect from '../ui/WizardSelect.vue';
import WizardVUMeter from '../ui/WizardVUMeter.vue';


const {canMoveNext, canMovePrev} = useStepStore(); // Navigation logic
const {
  words, selected, selectedArray, initSelection, moveForward, moveBackward,
} = useListStore(); // List-related logic

// Store reference for metadata and status
const recordStore = useRecordStore();

// Reactive states
const metadata = reactive(recordStore.metadata); // Language, media type, etc.
const status = reactive(recordStore.status); // Recording status
const statusCount = reactive(recordStore.statusCount); // Track counts for errors, stashing, etc.
const isRecording = ref(false);
const saturated = ref(false);
const vumeter = ref(0);
const countdown = ref(0);

// Audio and video parameters
const audioParams = reactive({
  startThreshold: 0.1,
  stopThreshold: 0.05,
  saturationThreshold: 0.99,
  stopDuration: 0.3,
  marginBefore: 0.25,
  marginAfter: 0.25,
});

const videoParams = reactive({
  recordDuration: '3',
  beforeStart: '5',
});

const videoStream = ref(null);
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
  recordStore.clearRecord(word); // Reset the record using store
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
    const audio = new Audio(recordStore.getMediaUrl(word)); // Use appropriate URL retrieval method
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
  if (metadata.media === 'audio') {
    cancelRecord();
    // Update recorder with new audio parameters if needed
  }
}, {deep: true});

watch(videoParams, () => {
  if (metadata.media === 'video') {
    cancelRecord();
    // Update recorder with new video parameters if needed
  }
}, {deep: true});

// Lifecycle hook: onMounted
onMounted(() => {
  // Initialize recorder logic
  if (metadata.media === 'audio') {
    recorder = new AudioRecorder(audioParams); // Replace with your actual recorder implementation
  } else {
    recorder = new VideoRecorder(videoParams); // Placeholder for video recorder
  }

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
        removeRecord(recordStore.words[recordStore.selected]); // Use the store for word management
        break;
      case ' ':
        toggleRecord();
        break;
      case 'p':
        playRecord(recordStore.words[recordStore.selected]); // Use the store for playing records
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
  <div class="studio">
    <div class="wizard-page-title">
      <h2>Studio Recording</h2>
      <WizardPopup>
        <template #trigger>
          <i id="mwe-rws-settings" class="mwe-rw-topicon"></i>
        </template>
        <template #content>
          <!-- Audio Settings -->
          <div id="mwe-rws-settings-audio" v-if="metadata.media === 'audio'">
            <h4>Audio Recording Settings</h4>
            <label>Start Threshold</label>
            <WizardNumber v-model="audioParams.startThreshold" :min="0" :max="100" :percentage="true"/>
            <label>Stop Threshold</label>
            <WizardNumber v-model="audioParams.stopThreshold" :min="0" :max="100" :percentage="true"/>
            <label>Saturation Threshold</label>
            <WizardNumber v-model="audioParams.saturationThreshold" :min="0" :max="100" :percentage="true"/>
            <label>Stop Duration</label>
            <WizardNumber v-model="audioParams.stopDuration" :min="0" :max="5" :step="0.05"/>
            <label>Margin Before</label>
            <WizardNumber v-model="audioParams.marginBefore" :min="0" :max="5" :step="0.05"/>
            <label>Margin After</label>
            <WizardNumber v-model="audioParams.marginAfter" :min="0" :max="5" :step="0.05"/>
          </div>
          <!-- Video Settings -->
          <div id="mwe-rws-settings-video" v-if="metadata.media === 'video'">
            <h4>Video Recording Settings</h4>
            <label>Record Duration</label>
            <WizardSelect v-model="videoParams.recordDuration" :options="[
                          { data: '2', label: '2 seconds' },
                          { data: '3', label: '3 seconds' },
                          { data: '5', label: '5 seconds' }
                        ]"/>
            <label>Delay Between Records</label>
            <WizardSelect v-model="videoParams.beforeStart" :options="[
                          { data: '3', label: '3 seconds' },
                          { data: '5', label: '5 seconds' },
                          { data: '10', label: '10 seconds' }
                        ]"/>
          </div>
        </template>
      </WizardPopup>

      <!-- Shortcut Dialog -->
      <WizardDialog name="shortcuts" size="large">
        <template #trigger>
          <i id="mwe-rws-shortcuts" class="mwe-rw-topicon"></i>
        </template>
        <template #content>
          <div id="mwe-rws-shortcuts-content">
            <h4>Shortcuts</h4>
            <section>
              <div>
                <kbd class="mwe-rw-key">←</kbd>
                <div>Previous Word</div>
              </div>
              <div>
                <kbd class="mwe-rw-key">→</kbd>
                <div>Next Word</div>
              </div>
              <div>
                <kbd class="mwe-rw-key">del</kbd>
                <div>Delete Recording</div>
              </div>
              <div>
                <kbd class="mwe-rw-key">P</kbd>
                <div>Listen to Recording</div>
              </div>
              <div>
                <kbd class="mwe-rw-key mwe-rw-key-long">space</kbd>
                <div>Toggle Recording</div>
              </div>
            </section>
          </div>
        </template>
      </WizardDialog>
    </div>

    <!-- Recording Section -->
    <div class="mwe-rw-section-group"
         :class="(metadata.media === 'audio' ? 'mwe-rws-audio' : 'mwe-rws-video') + (isRecording ? ' mwe-rws-recording' : '')">
      <section>
        <ul id="mwe-rws-list" class="mwe-rw-list">
          <li v-for="(word, index) in recordStore.words" :key="index" @click="playRecord(word)">
            {{ word }}
          </li>
        </ul>
      </section>

      <section>
        <div id="mwe-rws-core" class="mwe-rw-core">
          <div id="mwe-rws-itembox" class="mwe-rw-itembox">
            <div id="mwe-rws-item" class="mwe-rw-item">{{ recordStore.words[0] }}</div>
            <WizardButton id="mwe-rws-skip" label="Skip" icon="next" :framed="false" @click="moveForward"/>
          </div>
          <video id="mwe-rws-videoplayer" v-if="metadata.media === 'video'" controls></video>
        </div>

        <div id="mwe-rws-actions" class="mwe-rw-actions">
          <WizardButton
              id="mwe-rws-record"
              icon="ll-record"
              flags="primary progressive"
              @click="toggleRecord"
              :disabled="metadata.media === 'video' && videoStream === null"
          />
          <WizardVUMeter id="mwe-rws-vumeter" :value="vumeter" :class="{ 'mwe-rws-saturated': saturated }"/>

          <div id="mwe-rws-record-indicator">
            <div v-if="countdown === 0 && !saturated" id="mwe-rws-recording"></div>
            <div v-if="saturated">SAT</div>
            <div v-if="countdown > 0">{{ countdown }}</div>
          </div>

          <div id="mwe-rws-counter" class="mwe-rw-counter">
            <span>{{ statusCount.stashed }}</span> / <span>{{ recordStore.words.length }}</span>
            <span class="mwe-rw-othercounter">
                            <span class="mwe-rw-errorcounter" v-if="statusCount.error > 0">
                                <i></i>
                                <span>{{ statusCount.error }}</span>
                            </span>
                            <span class="mwe-rw-progresscounter" v-if="statusCount.stashing > 0">
                                <i></i>
                                <span>{{ statusCount.stashing }}</span>
                            </span>
                        </span>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>
