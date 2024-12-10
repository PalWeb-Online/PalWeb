<script setup>
import {onMounted, reactive, ref, watch} from 'vue';
import {useRecordStore} from '../store/RecordStore';
import WizardDropdown from '../ui/WizardDropdown.vue';
import WizardButton from "../ui/WizardButton.vue";
import {useStateStore} from "../store/StateStore.js";
import LinguaRecorder from "../../../utils/LinguaRecorder.js";
import WizardDialog from "../ui/WizardDialog.vue";

const RecordStore = useRecordStore();
const dialects = ref([]);
const locations = ref([]);
const genders = ref([
    {data: 'male', label: 'Male'},
    {data: 'female', label: 'Female'},
    {data: 'other', label: 'Other'},
]);
const levels = ref([
    {data: '1', label: 'Beginner'},
    {data: '2', label: 'Intermediate'},
    {data: '3', label: 'Advanced'},
    {data: '4', label: 'Fluent'},
    {data: '5', label: 'Native'},
]);

const fetchSpeakerOptions = async () => {
    try {
        const response = await axios.get('/record/options');

        if (response.data) {
            dialects.value = response.data.dialects;
            locations.value = response.data.locations;
        }

    } catch (error) {
        console.error('Loading Failed', error);
    }
}

function validateForm() {
    const speaker = RecordStore.data.speaker;
    if (!speaker.dialect_id || !speaker.location_id || !speaker.gender) {
        alert('Please fill out all the fields.');
        return false;
    }
    return true;
}

const StateStore = useStateStore();
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
        StateStore.setPermission(true);
        recorder.value.on('stopped', testPlay);
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

const testRecord = () => {
    if (StateStore.data.testState === 'ready') {

        StateStore.data.testState = 'speak';
        recorder.value.start();

        timer.value = setTimeout(() => {
            recorder.value.stop();
        }, 5000);
    }
};

const testPlay = (record) => {
    StateStore.data.testState = 'check';
    clearTimeout(timer.value);

    const audioNode = record.getAudioElement();
    audioNode.play();

    setTimeout(() => {
        StateStore.data.testState = 'ready';
    }, record.getDuration() * 1000 + 300);
};

onMounted(async () => {
    getAudioStream();
    await RecordStore.fetchSpeaker();
    await fetchSpeakerOptions();
});

watch(StateStore.data.testState, (newState) => {
    if (newState === false) {
        getAudioStream();
    } else if (!recorder.value) {
        unloadRecorder();
    }
});
</script>

<template>
    <div class="wizard-page-title">
        <h2>Speaker</h2>
    </div>

    <template v-if="!StateStore.data.hasPermission">
        <div class="tip">
            <div class="material-symbols-rounded">info</div>
            <div class="tip-content">
                <p>Welcome to the PalWeb <b>Record Wizard</b>! Allow the browser to use your mic in order to proceed.
                </p>
            </div>
        </div>

        <div class="wizard-section-container">
            <section style="flex-grow: 1; grid-template-rows: auto">
                <WizardButton id="mwe-rwt-reopenpopup" label="prompt again"
                              @click="getAudioStream"/>
            </section>
        </div>
    </template>

    <template v-else>
        <div class="wizard-section-container">
            <section>
                <div class="rw-speaker-profile-head">
                    <div class="rw-speaker-name">Welcome, {{ RecordStore.data.speaker.name ?? 'Loading...' }}!</div>
                    <div class="rw-test-info" v-if="!RecordStore.data.speaker.exists">
                        <p>It looks like you don't have a Speaker profile yet. Let's get you set up. Fill out this form
                            & click Create to get started. You can change these details at any time.</p>
                    </div>
                    <div class="rw-test-info" v-else>
                        <p>Here is the information you have provided for your Speaker profile. If everything looks good,
                            proceed to the next step! Don't forget to test your mic! (If you need help configuring your mic, visit <a
                                href="https://lingualibre.org/wiki/Help:Configure_your_microphone" target="_blank">this
                                page</a>.)</p>
                    </div>
                </div>
                <div class="rw-speaker-field">
                    <label for="dialect">Dialect</label>
                    <WizardDropdown
                        id="dialect"
                        :options="dialects.map(dialect => ({ data: dialect.id, label: dialect.name }))"
                        v-model="RecordStore.data.speaker.dialect_id"
                    />
                </div>
                <div class="rw-speaker-field">
                    <label for="location">Location</label>
                    <WizardDropdown
                        id="location"
                        :options="locations.map(location => ({ data: location.id, label: location.name }))"
                        v-model="RecordStore.data.speaker.location_id"
                    />
                </div>
                <div class="rw-speaker-field">
                    <label for="location">Fluency</label>
                    <WizardDropdown
                        id="location"
                        :options="levels"
                        v-model="RecordStore.data.speaker.fluency"
                    />
                </div>
                <div class="rw-speaker-field">
                    <label for="gender">Gender</label>
                    <WizardDropdown
                        id="gender"
                        :options="genders"
                        v-model="RecordStore.data.speaker.gender"
                    />
                </div>

                <WizardButton
                    :label="RecordStore.data.speaker.exists ? 'Update' : 'Create'"
                    @click="() => { if (validateForm()) RecordStore.saveSpeaker(); }"
                />
            </section>
            <section>
                <div class="rw-test-booth">
                    <div class="user-avatar">
                        <img alt="Profile Picture"
                             :src="`/img/avatars/${ RecordStore.data.speaker.avatar }`"/>
                    </div>
                    <img
                        v-if="StateStore.data.testState !== 'ready'"
                        class="rw-test-booth-prompt"
                        :src="`/img/${
                                                StateStore.data.testState === 'speak' ? 'speak' : 'listen'
                                            }.svg`"
                        alt="Prompt"
                    />
                </div>
                <div class="rw-test-status">
                    <div class="rw-test-status-info" v-if="StateStore.data.testState === 'ready'">
                        <div>Waiting</div>
                        <div>Click to test your mic.</div>
                    </div>
                    <div class="rw-test-status-info" v-else-if="StateStore.data.testState === 'speak'">
                        <div>Recording</div>
                        <div>Speak into the mic now.</div>
                    </div>
                    <div class="rw-test-status-info" v-else-if="StateStore.data.testState === 'check'">
                        <div>Listening</div>
                        <div>How does the audio sound?</div>
                    </div>
                    <img
                        :class="`rw-test-button ${StateStore.data.testState !== 'ready' ? 'disabled' : ''}`"
                        :src="`/img/${
                                                StateStore.data.testState === 'speak' ? 'record' :
                                                StateStore.data.testState === 'check' ? 'stop' : 'play'
                                            }.svg`"
                        alt="Test"
                        @click="testRecord"
                    />
                </div>
            </section>
        </div>
    </template>
</template>
