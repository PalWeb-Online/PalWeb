<script setup>
import {onMounted, reactive, ref, watch} from 'vue';
import WizardButton from '../ui/WizardButton.vue'; // Adjust the path if necessary

// Reactive state
const isBrowserReady = ref(false);
const mictesterState = ref('init'); // init -> wait -> record -> play
const recorder = ref(null);
const errorMessageAssociation = reactive({
    AbortError: 'Technical issue with the microphone.',
    NotAllowedError: 'Microphone access was not allowed.',
    NotFoundError: 'Microphone not found.',
    NotReadableError: 'Technical issue with the microphone.',
    OverconstrainedError: 'Microphone not found.',
    SecurityError: 'Technical issue with the microphone.',
});
const timer = ref(null);

// Methods
const getAudioStream = () => {
    unloadRecorder(); // Unload any previous recorder

    // Simulate getting the audio stream using LinguaRecorder (mock here)
    recorder.value = new LinguaRecorder({
        autoStart: true,
        autoStop: true,
    });

    recorder.value.on('readyFail', showError);
    recorder.value.on('ready', () => {
        isBrowserReady.value = true;
        recorder.value.on('stoped', mictesterPlay);
    });
};

const unloadRecorder = () => {
    if (recorder.value) {
        recorder.value.stop();
        recorder.value.off('readyFail');
        recorder.value.off('ready');
        recorder.value.off('stoped');
        recorder.value.close();
        recorder.value = null;
    }
};

const showError = (mediaStreamError) => {
    let message = 'An unknown error occurred.';

    if (errorMessageAssociation[mediaStreamError.name]) {
        message = errorMessageAssociation[mediaStreamError.name];
    }

    alert(message); // Replace OO.ui.alert with native alert for now
};

const mictesterRecord = () => {
    mictesterState.value = 'wait';

    setTimeout(() => {
        mictesterState.value = 'record';
        recorder.value.start();

        timer.value = setTimeout(() => {
            recorder.value.stop();
        }, 8000); // Record for 8 seconds
    }, 1100); // Simulate delay before recording
};

const mictesterPlay = (record) => {
    mictesterState.value = 'wait';

    setTimeout(() => {
        mictesterState.value = 'play';
        clearTimeout(timer.value);

        const audioNode = record.getAudioElement();
        audioNode.play();

        setTimeout(() => {
            mictesterState.value = 'init'; // Return to initial state after playback
        }, record.getDuration() * 1000 + 300);
    }, 1100); // Simulate delay before playing
};

// Lifecycle hooks
onMounted(() => {
    getAudioStream();
});

watch(mictesterState, (newState) => {
    if (newState === 'init') {
        getAudioStream();
    } else if (!recorder.value) {
        unloadRecorder();
    }
});
</script>

<template>
    <div>
        <div class="mwe-rw-content-title">
            <h3>Tutorial</h3>
        </div>

        <!-- Section when the browser is not ready -->
        <div class="mwe-rw-section-group" v-if="!isBrowserReady">
            <section>
                <img src="/path/to/icons/no-mic.svg" width="40" height="40" alt="No microphone">
                <p>Activate your microphone to proceed.</p>
                <WizardButton id="mwe-rwt-reopenpopup" label="Reopen microphone settings popup"
                              @click="getAudioStream"/>
            </section>
        </div>

        <!-- Section when the browser is ready -->
        <div class="mwe-rw-section-group" v-if="isBrowserReady">
            <section>
                <p>Here is how to use the recording wizard.</p>
                <div id="mwe-rwt-info">
                    <p>Please follow the instructions to ensure the best quality.</p>
                </div>
            </section>
            <section>
                <p>Mic Tester Info: Make sure your microphone works correctly.</p>
                <WizardButton
                    id="mwe-rwt-testbutton"
                    v-if="mictesterState === 'init'"
                    label="Start Mic Test"
                    @click="mictesterRecord"
                />
                <div id="mwe-rwt-testrecord" v-if="mictesterState === 'record'">
                    Recording...
                </div>
                <div id="mwe-rwt-testplay" v-if="mictesterState === 'play'">
                    Playing the recording...
                </div>
                <div id="mwe-rwt-testwait" v-if="mictesterState === 'wait'">
                    Please wait...
                </div>
            </section>
        </div>
    </div>
</template>

<style scoped>
.mwe-rw-content-title {
    margin-bottom: 20px;
}

.mwe-rw-section-group {
    margin-top: 20px;
}

img {
    display: block;
    margin-bottom: 10px;
}

p {
    margin-bottom: 10px;
}
</style>
