<script setup>
import {computed, onMounted, ref, watch} from 'vue';
import {useSoundBoothStore} from "../stores/SoundBoothStore.js";
import {useRecordStore} from "../stores/RecordStore.js";
import WizardDropdown from '../ui/WizardDropdown.vue';
import {useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {useUserStore} from "../../../../stores/UserStore.js";
import PopupWindow from "../../../../components/Modals/PopupWindow.vue";
import AppTip from "../../../../components/AppTip.vue";
import {useNavGuard} from "../../../../composables/NavGuard.js";
import ModalWrapper from "../../../../components/Modals/ModalWrapper.vue";
import NavGuard from "../../../../components/Modals/NavGuard.vue";

const UserStore = useUserStore();
const SoundBoothStore = useSoundBoothStore();
const RecordStore = useRecordStore();

const testStates = [
    'waiting',
    'recording',
    'listening',
];

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

const form = useForm({
    dialect_id: SoundBoothStore.speaker.dialect?.id || '',
    location_id: SoundBoothStore.speaker.location?.id || '',
    fluency: SoundBoothStore.speaker.fluency || '',
    gender: SoundBoothStore.speaker.gender || '',
})

watch(() => SoundBoothStore.speaker, (newSpeaker) => {
    if (newSpeaker && newSpeaker.id !== null) {
        form.dialect_id = newSpeaker.dialect?.id || '';
        form.location_id = newSpeaker.location?.id || '';
        form.fluency = newSpeaker.fluency || '';
        form.gender = newSpeaker.gender || '';
        form.defaults();
    }
}, {deep: true});

const isSaving = ref(false);

const hasNavigationGuard = computed(() => {
    return form.isDirty && !isSaving.value;
});

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

const isValidRequest = computed(() => {
    return Object.values(form.data()).every(value => value);
});

const fetchSpeakerOptions = async () => {
    try {
        const response = await axios.get(route('speaker.options'));

        if (response.data) {
            dialects.value = response.data.dialects;
            locations.value = response.data.locations;
        }

    } catch (error) {
        console.error('Loading Failed', error);
    }
}

const saveSpeaker = async () => {
    isSaving.value = true;

    form.post(route('speaker.store'), {
        onSuccess: () => {
            form.defaults();
        },
        onError: () => {
            NotificationStore.addNotification('Oh no! Your Speaker profile could not be saved.', 'error');
        },
        onFinish: () => {
            isSaving.value = false;
        }
    });
};

onMounted(async () => {
    RecordStore.openRecorder(RecordStore.audioParams);
    SoundBoothStore.data.testState = 'waiting';
    await fetchSpeakerOptions();
    await RecordStore.clearStash();
});
</script>

<template>
    <div class="window-section-head">
        <h2>{{ $t('speaker.speaker') }}</h2>

        <PopupWindow title="Sound Booth (Speaker)">
            <div>What is my Speaker profile?</div>
            <p>Your Speaker profile contains linguistic data about you that will be connected to every Recording you
                create, so that others can know the dialect & other sociolinguistic information behind what they're
                hearing. Your Speaker profile is distinct from your User profile; it does not include your name,
                etc. By default, however, your Recordings will link to your User profile. If you would like for your
                Speaker profile to remain anonymous, simply return to the Dashboard & set your User profile to
                Private. You can change this at any time.</p>
            <div>What is my Dialect?</div>
            <p>Your Dialect is the variety of Palestinian Arabic that you speak, or that you intend to represent in
                your Audios. In the Sound Booth, you will only be shown Pronunciation items valid for the selected
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
        </PopupWindow>
    </div>

    <template v-if="!SoundBoothStore.data.hasPermission">
        <AppTip>
            <p>Allow the browser to use your mic in order to proceed.
                <button @click="RecordStore.openRecorder(RecordStore.audioParams)">Click here to prompt again.</button>
            </p>
        </AppTip>
    </template>

    <template v-else>
        <AppTip>
            <p v-if="!SoundBoothStore.speaker.id">It looks like you don't have a Speaker profile yet.
                Let's get you set up: fill out this form & click Create to get started. Refer to the Info
                box in
                the top-right corner for more detail on the meaning of these fields. <b>Once your Speaker
                    profile is created, your Dialect can only be changed by the site administrator.</b></p>
            <p v-else>Check your Speaker profile & test your mic before proceeding. (If you need help configuring
                your mic, visit <a href="https://lingualibre.org/wiki/Help:Configure_your_microphone"
                                   target="_blank">this page</a>.)</p>
            <p>If you'd like for your Speaker profile to remain anonymous & not link to your user account, set your
                Profile to Private; others will still be able to interact with any Audios you have recorded. You may
                change this setting at any time.</p>
        </AppTip>
        <div class="rw-page__speaker">
            <div class="user-item l">
                <div class="rw-test-booth">
                    <Link :disabled="!!RecordStore.recorder"
                          :href="SoundBoothStore.speaker.id ? route('speaker.show', SoundBoothStore.speaker.id) : '#'"
                          class="user-avatar">
                        <img alt="Profile Picture"
                             :src="`/img/avatars/${ UserStore.user.private ? 'palweb01.jpg' : UserStore.user.avatar }`"/>
                    </Link>
                    <img
                        v-if="SoundBoothStore.data.testState !== 'waiting'"
                        class="rw-test-booth-prompt"
                        :src="`/img/${SoundBoothStore.data.testState === 'recording' ? 'speak' : 'listen'}.svg`"
                        alt="Prompt"
                    />
                </div>

                <div class="user-data-wrapper">
                    <div class="user-name">
                        <div class="user-name-ar">{{ UserStore.user.private ? 'مجهول' : UserStore.user.ar_name }}</div>
                        <div class="user-name-en">
                            <div>
                                {{
                                    UserStore.user.private ? 'Speaker #' + SoundBoothStore.speaker.id : UserStore.user.name
                                }}
                            </div>
                            <div>
                                {{ UserStore.user.private ? '[anonymous]' : UserStore.user.username }}
                            </div>
                        </div>
                    </div>

                    <div class="speaker-data">
                        <div class="speaker-data-head">
                            <div>{{ $t('speaker.speaker')}}</div>
                            <button :disabled="form.processing || !hasNavigationGuard || !isValidRequest"
                                    class="material-symbols-rounded" @click="saveSpeaker">
                                save
                            </button>
                        </div>
                        <div class="speaker-data-row">
                            <div>{{ $t('speaker.dialect') }}</div>
                            <WizardDropdown
                                v-model="form.dialect_id"
                                :options="dialects.map(dialect => ({ data: dialect.id, label: dialect.name }))"
                                :disabled="!!SoundBoothStore.speaker.id"
                            />
                        </div>
                        <div class="speaker-data-row">
                            <div>{{ $t('speaker.location') }}</div>
                            <WizardDropdown
                                v-model="form.location_id"
                                :options="locations.map(location => ({ data: location.id, label: location.name_ar }))"
                            />
                        </div>
                        <div class="speaker-data-row">
                            <div>{{ $t('speaker.fluency') }}</div>
                            <WizardDropdown
                                v-model="form.fluency"
                                :options="levels"
                            />
                        </div>
                        <div class="speaker-data-row">
                            <div>{{ $t('speaker.gender') }}</div>
                            <WizardDropdown
                                v-model="form.gender"
                                :options="genders"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="rw-test-status">
                <template v-for="state in testStates" :key="state">
                    <div class="rw-test-status-info" v-if="SoundBoothStore.data.testState === state">
                        <div>{{ $t('sound-booth.test.' + state + '.title') }}</div>
                        <div>{{ $t('sound-booth.test.' + state + '.subtitle') }}</div>
                    </div>
                </template>

                <img
                    :class="`rw-test-button ${SoundBoothStore.data.testState !== 'waiting' ? 'disabled' : ''}`"
                    :src="`/img/${
                                                SoundBoothStore.data.testState === 'recording' ? 'record' :
                                                SoundBoothStore.data.testState === 'listening' ? 'stop' : 'play'
                                            }.svg`"
                    alt="Test"
                    @click="RecordStore.testRecord"
                />
            </div>
        </div>
    </template>
    <ModalWrapper v-model="showAlert">
        <NavGuard
            message="You have unsaved changes. Are you sure you want to leave this page? Unsaved changes will be lost."
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>
</template>
