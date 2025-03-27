<script setup>
import {computed, onMounted, reactive, ref} from 'vue';
import {useRecordWizardStore} from "../stores/RecordWizardStore.js";
import {useRecordStore} from "../stores/RecordStore.js";
import WizardDropdown from '../ui/WizardDropdown.vue';
import WizardButton from "../ui/WizardButton.vue";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {useUserStore} from "../../../../stores/UserStore.js";
import {useNotificationStore} from "../../../../stores/NotificationStore.js";
import LinguaRecorder from "../../../../utils/LinguaRecorder.js";
import PopupWindow from "../../../../components/Modals/PopupWindow.vue";

const UserStore = useUserStore();
const RecordStore = useRecordStore();
const RecordWizardStore = useRecordWizardStore();
const NotificationStore = useNotificationStore();

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

const isFormValid = computed(() => {
    const speaker = RecordWizardStore.speaker;
    return speaker.dialect.id && speaker.location.id && speaker.gender;
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
        RecordWizardStore.setPermission(true);
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
    if (RecordWizardStore.data.testState === 'ready') {

        RecordWizardStore.data.testState = 'speak';
        recorder.value.start();

        timer.value = setTimeout(() => {
            recorder.value.stop();
        }, 5000);
    }
};

const testPlay = (record) => {
    RecordWizardStore.data.testState = 'check';
    clearTimeout(timer.value);

    const audioNode = record.getAudioElement();
    audioNode.play();

    setTimeout(() => {
        RecordWizardStore.data.testState = 'ready';
    }, record.getDuration() * 1000 + 300);
};

const fetchSpeakerOptions = async () => {
    try {
        const response = await axios.get('/workbench/record-wizard/options');

        if (response.data) {
            dialects.value = response.data.dialects;
            locations.value = response.data.locations;
        }

    } catch (error) {
        console.error('Loading Failed', error);
    }
}

const saveSpeaker = async () => {
    router.post(route('speaker.store'), {
            dialect_id: RecordWizardStore.speaker.dialect.id,
            location_id: RecordWizardStore.speaker.location.id,
            fluency: RecordWizardStore.speaker.fluency,
            gender: RecordWizardStore.speaker.gender
        },
        {
            onSuccess: () => {
                NotificationStore.addNotification('Your Speaker profile has been saved!');

                // todo: why does this never work?
                RecordWizardStore.speaker.id = $page.props.flash?.id;
            },

            onError: () => {
                NotificationStore.addNotification('Oh no! Your Speaker profile could not be saved.');
            },
        }
    );
};

onMounted(async () => {
    getAudioStream();
    await fetchSpeakerOptions();
    await RecordStore.clearStash();
});
</script>

<template>
    <div class="rw-container-head window-head">
        <h2>Speaker</h2>

        <PopupWindow title="Record Wizard: Speaker">
            <template #trigger>
                <div class="material-symbols-rounded">help</div>
            </template>
            <template #content>
                <div>What is my Speaker profile?</div>
                <p>Your Speaker profile contains linguistic data about you that will be connected to every Recording you
                    create, so that others can know the dialect & other sociolinguistic information behind what they're
                    hearing. Your Speaker profile is distinct from your User profile; it does not include your name,
                    etc. By default, however, your Recordings will link to your User profile. If you would like for your
                    Speaker profile to remain anonymous, simply return to the Dashboard & set your User profile to
                    Private. You can change this at any time.</p>

                <div>What is my Dialect?</div>
                <p>Your Dialect is the variety of Palestinian Arabic that you speak, or that you intend to represent in
                    your Audios. In the Record Wizard, you will only be shown Pronunciation items valid for the selected
                    Dialect. PalWeb is an ongoing research project, so the list of Dialects is provisional. If what you
                    consider to be your Dialect does not appear on the list, simply choose the closest one. You may see
                    discrepancies between the items presented to you & the way you pronounce them; this is absolutely
                    normal. Just record the items in the way that comes naturally to you. <b>Once your Speaker profile
                        is created, your Dialect can only be changed by the site administrator.</b> Check the <b>User
                        Guide</b> in the Wiki for more information.</p>

                <div>What is my Location?</div>
                <p>Your Location is the place where you learned Arabic, or the place that the people with whom you
                    learned Arabic (e.g. your relatives) are from. It is up to you to decide the most
                    appropriate choice to select. If you have lived most of your life in a given town, but your family &
                    you have an accent characteristic of another town, then the town of your ancestry may be a more
                    appropriate selection. Select the Location that you feel best represents your manner of
                    speaking.</p>
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
            </template>
        </PopupWindow>
    </div>

    <template v-if="!RecordWizardStore.data.hasPermission">
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
                    <div class="rw-speaker-name">Welcome, {{ UserStore.user.name }}!</div>
                    <p v-if="!RecordWizardStore.speaker.id">It looks like you don't have a Speaker profile yet.
                        Let's get you set up: fill out this form & click Create to get started. Refer to the Info box in
                        the top-right corner for more detail on the meaning of these fields. <b>Once your Speaker
                            profile is created, your Dialect can only be changed by the site administrator.</b></p>
                    <p v-else>Here is the information you have provided for your Speaker profile. If everything looks
                        good, proceed to the next step! Don't forget to test your mic! (If you need help configuring
                        your mic, visit <a href="https://lingualibre.org/wiki/Help:Configure_your_microphone"
                                           target="_blank">this page</a>.)</p>

                </div>
                <div class="rw-speaker-field">
                    <label for="dialect">Dialect</label>
                    <WizardDropdown
                        v-model="RecordWizardStore.speaker.dialect.id"
                        :options="dialects.map(dialect => ({ data: dialect.id, label: dialect.name }))"
                        :disabled="RecordWizardStore.speaker.id"
                    />
                </div>
                <div class="rw-speaker-field">
                    <label for="location">Location</label>
                    <WizardDropdown
                        v-model="RecordWizardStore.speaker.location.id"
                        :options="locations.map(location => ({ data: location.id, label: location.name_ar }))"
                    />
                </div>
                <div class="rw-speaker-field">
                    <label for="location">Fluency</label>
                    <WizardDropdown
                        v-model="RecordWizardStore.speaker.fluency"
                        :options="levels"
                    />
                </div>
                <div class="rw-speaker-field">
                    <label for="gender">Gender</label>
                    <WizardDropdown
                        v-model="RecordWizardStore.speaker.gender"
                        :options="genders"
                    />
                </div>

                <WizardButton
                    :label="RecordWizardStore.speaker.id ? 'Update' : 'Create'"
                    :disabled="!isFormValid"
                    @click="saveSpeaker"
                />
            </section>
            <section>
                <div class="rw-test-booth">
                    <div class="user-avatar">
                        <img alt="Profile Picture"
                             :src="`/img/avatars/${ UserStore.user.avatar }`"/>
                    </div>
                    <img
                        v-if="RecordWizardStore.data.testState !== 'ready'"
                        class="rw-test-booth-prompt"
                        :src="`/img/${
                                                RecordWizardStore.data.testState === 'speak' ? 'speak' : 'listen'
                                            }.svg`"
                        alt="Prompt"
                    />
                </div>
                <div class="rw-test-status">
                    <div class="rw-test-status-info" v-if="RecordWizardStore.data.testState === 'ready'">
                        <div>Waiting</div>
                        <div>Click to test your mic.</div>
                    </div>
                    <div class="rw-test-status-info" v-else-if="RecordWizardStore.data.testState === 'speak'">
                        <div>Recording</div>
                        <div>Speak into the mic now.</div>
                    </div>
                    <div class="rw-test-status-info" v-else-if="RecordWizardStore.data.testState === 'check'">
                        <div>Listening</div>
                        <div>How does the audio sound?</div>
                    </div>
                    <img
                        :class="`rw-test-button ${RecordWizardStore.data.testState !== 'ready' ? 'disabled' : ''}`"
                        :src="`/img/${
                                                RecordWizardStore.data.testState === 'speak' ? 'record' :
                                                RecordWizardStore.data.testState === 'check' ? 'stop' : 'play'
                                            }.svg`"
                        alt="Test"
                        @click="testRecord"
                    />
                </div>
            </section>
        </div>
    </template>
</template>
