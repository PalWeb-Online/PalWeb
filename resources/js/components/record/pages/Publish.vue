<script setup>
import {computed, onMounted, reactive, ref} from 'vue';
import WizardCheckbox from '../ui/WizardCheckbox.vue';
import WizardButton from '../ui/WizardButton.vue';
import WizardProgressBar from '../ui/WizardProgressBar.vue';

// Placeholder data structures from the original
const words = ref(['word1', 'word2']); // Word list
const selectedArray = ref([]); // Array for tracking selected words
const metadata = reactive({
    media: 'audio', // Could be 'audio' or 'video'
    language: 'en', // Example metadata fields
    speaker: {qid: 'Q12345'},
});
const status = reactive({
    word1: 'done', // Word statuses
    word2: 'uploading',
});
const errors = reactive({
    word1: false,
    word2: 'Error with word2',
});
const checkboxes = reactive({
    word1: false,
    word2: true,
});
const statusCount = reactive({
    done: 1,
    error: 1,
    uploading: 1,
    finalizing: 0,
});
const state = reactive({
    step: 'publish',
    isPublishing: false,
});
const mediaUrl = ref('path/to/media.mp3'); // Example media URL

// Additional reactive data
const forceUpdate = ref(0);
const $wordsShortlist = ref([]); // For storing filtered word list
const selected = ref(0); // Currently selected word
const total = computed(() => {
    forceUpdate.value++; // Trigger recompute when forceUpdate changes
    return $wordsShortlist.value.reduce((acc, word) => {
        if (checkboxes[word]) acc++;
        return acc;
    }, 0);
});
const $selector = ref('#mwe-rwp-core audio');

// Methods
const isSelectable = (word) => {
    return !['up', 'ready', 'stashing'].includes(status[word]);
};

const selectWord = (index) => {
    selected.value = index;
};

const moveForward = () => {
    if (selected.value < words.value.length - 1) {
        selectWord(selected.value + 1);
    }
};

const moveBackward = () => {
    if (selected.value > 0) {
        selectWord(selected.value - 1);
    }
};

const stopPlaying = () => {
    const element = document.querySelector($selector.value);
    if (element) {
        element.pause();
    }
};

const startPlaying = () => {
    const element = document.querySelector($selector.value);
    if (element) {
        element.play();
    }
};

const canMovePrev = () => {
    stopPlaying();
    return true;
};

const canMoveNext = () => {
    stopPlaying();
    if (!state.isPublishing) {
        state.isPublishing = true;
        $wordsShortlist.value.forEach((word) => {
            if (checkboxes[word]) {
                // Trigger publishing process for each word
                console.log(`Publishing ${word}`);
                // rw.store.record.doPublish(word);
            }
        });
        return false; // Block further navigation during publishing
    } else {
        state.isPublishing = false;
        // rw.store.record.clearAllPublishedRecords();
        // rw.store.config.pushPastRecords(metadata.language, metadata.speaker.qid, oldRecords);
        return true;
    }
};

// Lifecycle hooks
onMounted(() => {
    if (state.step === 'publish') {
        forceUpdate.value++; // Force recompute
        $selector.value = metadata.media === 'audio' ? '#mwe-rwp-core audio' : '#mwe-rwp-core video';
        $wordsShortlist.value = words.value.filter(isSelectable);
        selectWord(0); // Select the first word by default
    }
});

// Computed Properties
const mediaUrlComputed = computed(() => {
    // Assume we have access to a media URL provider
    return mediaUrl.value;
});
</script>

<template>
    <div class="wizard-page-title">
        <h2>Publish Recordings</h2>
    </div>

    <div class="mwe-rw-section-group">
        <section>
            <!-- Word List with Checkboxes -->
            <ul id="mwe-rwp-list" class="mwe-rw-list">
                <li v-for="(word, index) in words" :key="index" v-if="isSelectable(word)"
                    :class="`mwe-rwp-${status[word]} ${errors[word] !== false ? 'mwe-rw-error' : ''} ${selectedArray[index] || ''}`"
                    @click.self="selectWord(index)">
                    <WizardCheckbox v-model="checkboxes[word]" :disabled="state.isPublishing"/>
                    <label :for="'mwe-rwp-cb1-input'" v-html="word" :title="errors[word]"
                           @click="selectWord(index)"></label>
                </li>
            </ul>
        </section>

        <section>
            <!-- Playback Section -->
            <div id="mwe-rwp-core" class="mwe-rw-core">
                <div id="mwe-rwp-itembox" class="mwe-rw-itembox">
                    <WizardButton id="mwe-rwp-prev" icon="previous" :framed="false" @click="moveBackward"/>
                    <div id="mwe-rwp-item" class="mwe-rw-item" v-html="words[selected]"></div>
                    <WizardButton id="mwe-rwp-next" icon="next" :framed="false" @click="moveForward"/>
                </div>

                <!-- Audio/Video Controls -->
                <audio v-if="metadata.media === 'audio'" :src="mediaUrlComputed" controls autoplay></audio>
                <video v-if="metadata.media === 'video'" :src="mediaUrlComputed" controls autoplay></video>
            </div>

            <!-- Progress and Counter Section -->
            <div id="mwe-rwp-actions" class="mwe-rw-actions">
                <WizardProgressBar :value="(100 * statusCount.done / total).toString()"/>

                <div id="mwe-rwp-counter" class="mwe-rw-counter">
                    <span v-html="statusCount.done"></span> / <span v-html="total"></span>
                    <span class="mwe-rw-othercounter">
              <span class="mwe-rw-errorcounter" v-if="statusCount.error > 0">
                <i></i>
                <span v-html="statusCount.error"></span>
              </span>
              <span class="mwe-rw-progresscounter" v-if="statusCount.uploading + statusCount.finalizing > 0">
                <i></i>
                <span v-html="statusCount.uploading + statusCount.finalizing"></span>
              </span>
            </span>
                </div>
            </div>
        </section>
    </div>
</template>
