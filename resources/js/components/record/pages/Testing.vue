<script setup>
import {onMounted, reactive, ref, watch} from 'vue';
import {useStateStore} from "../store/StateStore";
import WizardButton from '../ui/WizardButton.vue';
import LinguaRecorder from "../../../utils/LinguaRecorder.js";

const StateStore = useStateStore();
const mictesterState = ref('init');
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

const getAudioStream = () => {
    unloadRecorder();

    // Simulate getting the audio stream using LinguaRecorder (mock here)
    recorder.value = new LinguaRecorder({
        autoStart: true,
        autoStop: true,
    });

    recorder.value.on('readyFail', showError);
    recorder.value.on('ready', () => {
        StateStore.setBrowserReady(true);
        recorder.value.on('stopped', mictesterPlay);
    });
};

const unloadRecorder = () => {
    if (recorder.value) {
        recorder.value.stop();
        recorder.value.off('readyFail');
        recorder.value.off('ready');
        recorder.value.off('stopped');
        recorder.value.close();
        recorder.value = null;
    }
};

const showError = (mediaStreamError) => {
    let message = 'An unknown error occurred.';

    if (errorMessageAssociation[mediaStreamError.name]) {
        message = errorMessageAssociation[mediaStreamError.name];
    }

    alert(message);
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
    <div class="wizard-page-title">
        <h2>Testing</h2>
    </div>

    <div class="wizard-section-container" v-if="!StateStore.data.isBrowserReady">
        <section>
            <img src="/path/to/icons/no-mic.svg" width="40" height="40" alt="No microphone">
            <p>Activate your microphone to proceed.</p>
            <WizardButton id="mwe-rwt-reopenpopup" label="Reopen microphone settings popup"
                          @click="getAudioStream"/>
        </section>
    </div>

    <div class="wizard-section-container" v-if="StateStore.data.isBrowserReady">
        <section>
            <p>Here's how to use the Record Wizard:</p>
            <ol>
                <li>When you're ready, click the "Test Me" button to run a recording test.</li>
                <li>Say something out loud!</li>
                <li>Listen to the recording & make sure it sounds right.</li>
                <li>If so, move onto the next step!</li>
                <li>If not, adjust your mic configuration as needed.</li>
            </ol>

            <div class="tip">
                <div class="material-symbols-rounded">info</div>
                <div class="tip-content">
                    <p>Visit <a href="https://lingualibre.org/wiki/Help:Configure_your_microphone" target="_blank">this
                        page</a> to
                        learn how to configure your microphone.</p>
                </div>
            </div>
        </section>
        <section>
            <p>Press the button below to start the mic test.</p>
            <WizardButton
                v-if="mictesterState === 'init'"
                label="Test Me"
                @click="mictesterRecord"
            />
            <div class="wizard-test-status" v-if="mictesterState === 'wait'">
                <img class="loading" src="/img/wait.svg" alt="Loading"/>
            </div>
            <div class="wizard-test-status is-recording" v-if="mictesterState === 'record'">
                <div>recording</div>
                <div>speak now</div>
            </div>
            <div class="wizard-test-status" v-if="mictesterState === 'play'">
                <div>listening</div>
                <div>check the recording</div>
            </div>
        </section>
    </div>
</template>
