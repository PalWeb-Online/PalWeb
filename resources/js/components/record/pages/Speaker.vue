<script setup>
import {computed, onMounted, reactive, ref} from 'vue';
import {useStateStore} from "../stores/StateStore.js";
import {useSpeakerStore} from "../stores/SpeakerStore.js";
import {useRecordStore} from '../stores/RecordStore';
import WizardDropdown from '../ui/WizardDropdown.vue';
import WizardButton from "../ui/WizardButton.vue";
import LinguaRecorder from "../../../utils/LinguaRecorder.js";
import AppDialog from "../../AppDialog.vue";

const StateStore = useStateStore();
const SpeakerStore = useSpeakerStore();
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
        const response = await axios.get('/dashboard/workbench/record-wizard/options');

        if (response.data) {
            dialects.value = response.data.dialects;
            locations.value = response.data.locations;
        }

    } catch (error) {
        console.error('Loading Failed', error);
    }
}

const isFormValid = computed(() => {
    const speaker = SpeakerStore.data.speaker;
    return speaker.dialect_id && speaker.location_id && speaker.gender;
});

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
    await SpeakerStore.fetchSpeaker();
    await fetchSpeakerOptions();
});
</script>

<template>
    <div class="rw-page-title">
        <h2>Speaker</h2>

        <AppDialog size="large">
            <template #trigger>
                <img alt="Info" src="/img/idea.svg"/>
            </template>
            <template #content>
                <div>What is my Speaker profile?</div>
                <p>Your Speaker profile contains linguistic data about you that will be connected to every
                    Recording you
                    create, so that others can know the dialect & other sociolinguistic information behind what
                    they're
                    hearing. Your Speaker profile is distinct from your User profile; it does not include your
                    name,
                    etc. By default, however, your Recordings will link to your User profile. If you would like
                    for your
                    Speaker profile to remain anonymous, simply return to the Dashboard & set your User profile
                    to
                    Private. You can change this at any time.</p>
                <div>What is my Location?</div>
                <p>Your Location is the place where you learned Arabic, or the place that the people with whom
                    you
                    learned Arabic (e.g. your relatives) are from. It is up to you to decide the most
                    appropriate choice
                    to select. If you have lived most of your life in a given town, but your family & you have
                    an accent
                    characteristic of another town, then the town of your ancestry may be a more appropriate
                    selection. Select the Location that you feel best represents your manner of speaking.</p>
                <p><b>Note:</b> These Locations were imported from Wikidata by means of a script. There was no
                    way to
                    reliably distinguish Israeli towns in the 1948 Territories from Palestinian ones, so all
                    towns
                    currently located in the 1948 Territories — including Israeli towns — are listed. Israeli
                    settlements in the West Bank & the Golan Heights have been discarded, however.
                </p>
                <div>What is my Fluency level?</div>
                <p>Your Fluency level reflects how natural your pronunciation is, according to your own
                    assessment. Only
                    choose <b>Native</b> if you were raised speaking the language & have used it throughout your
                    life,
                    especially in a region where the language is spoken natively. Choose <b>Fluent</b> if your
                    pronunciation of the language is naturalistic for any other reason. Most learners should not
                    select
                    anything higher than <b>Advanced</b>. Heritage speakers may fall anywhere on this spectrum.
                    Err on
                    the side of underestimation.</p>
                <div>License Information</div>
                <p>By using the PalWeb Record Wizard, you agree to the publication and use of your recordings under the
                    <a href="https://creativecommons.org/licenses/by/4.0/" target="_blank">Creative Commons CC BY
                        4.0</a> license. This license allows others to share, use, and adapt your recordings, even for
                    commercial purposes, provided they give proper attribution. Once published, you cannot revoke this
                    license, meaning you cannot withdraw or restrict the use of your recordings. However, PalWeb will
                    honor take-down requests to remove recordings from our platform.</p>
                <p>Your recordings may also be shared on third-party platforms like Wikimedia Commons to increase
                    accessibility and public utility. These platforms operate independently of PalWeb and have their own
                    removal and take-down policies; we cannot guarantee the removal of recordings from third-party
                    platforms once they are uploaded. For Wikimedia’s terms and policies, please visit <a
                        href="https://commons.wikimedia.org/wiki/Commons:Licensing" target="_blank">Wikimedia Commons
                        Licensing.</a></p>
            </template>
        </AppDialog>
    </div>

    <template v-if="!StateStore.data.hasPermission">
        <div class="tip">
            <div class="material-symbols-rounded">info</div>
            <div class="tip-content">
                <p>Welcome to the PalWeb <b>Record Wizard</b>! Allow the browser to use your mic in order to proceed.
                </p>
            </div>
        </div>

        <div class="rw-page__speaker">
            <section style="flex-grow: 1; grid-template-rows: auto">
                <WizardButton id="mwe-rwt-reopenpopup" label="prompt again"
                              @click="getAudioStream"/>
            </section>
        </div>
    </template>

    <template v-else>
        <div class="rw-page__speaker">
            <section>
                <div class="rw-speaker-profile-head">
                    <div class="rw-speaker-name">Welcome, {{ SpeakerStore.data.speaker.name ?? 'Loading...' }}!</div>
                    <p v-if="!SpeakerStore.data.speaker.exists">It looks like you don't have a Speaker profile yet.
                        Let's get you set up. Fill out this form & click Create to get started. You can change these
                        details at any time.</p>
                    <p v-else>Here is the information you have provided for your Speaker profile. If everything looks
                        good, proceed to the next step! Don't forget to test your mic! (If you need help configuring
                        your mic, visit <a href="https://lingualibre.org/wiki/Help:Configure_your_microphone"
                                           target="_blank">this page</a>.)</p>

                </div>
                <div class="rw-speaker-field">
                    <label for="dialect">Dialect</label>
                    <WizardDropdown
                        id="dialect"
                        :options="dialects.map(dialect => ({ data: dialect.id, label: dialect.name }))"
                        v-model="SpeakerStore.data.speaker.dialect_id"
                    />
                </div>
                <div class="rw-speaker-field">
                    <label for="location">Location</label>
                    <WizardDropdown
                        id="location"
                        :options="locations.map(location => ({ data: location.id, label: location.name }))"
                        v-model="SpeakerStore.data.speaker.location_id"
                    />
                </div>
                <div class="rw-speaker-field">
                    <label for="location">Fluency</label>
                    <WizardDropdown
                        id="location"
                        :options="levels"
                        v-model="SpeakerStore.data.speaker.fluency"
                    />
                </div>
                <div class="rw-speaker-field">
                    <label for="gender">Gender</label>
                    <WizardDropdown
                        id="gender"
                        :options="genders"
                        v-model="SpeakerStore.data.speaker.gender"
                    />
                </div>

                <WizardButton
                    :label="SpeakerStore.data.speaker.exists ? 'Update' : 'Create'"
                    :disabled="!isFormValid"
                    @click="SpeakerStore.saveSpeaker()"
                />
            </section>
            <section>
                <div class="rw-test-booth">
                    <div class="user-avatar">
                        <img alt="Profile Picture"
                             :src="`/img/avatars/${ SpeakerStore.data.speaker.avatar }`"/>
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
