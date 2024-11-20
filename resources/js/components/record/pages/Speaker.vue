<script setup>
import {onMounted, ref} from 'vue';
import {useRecordStore} from '../store/RecordStore';
import WizardDropdown from '../ui/WizardDropdown.vue';
import WizardSelect from '../ui/WizardSelect.vue';
import WizardButton from "../ui/WizardButton.vue";

const RecordStore = useRecordStore();
const dialects = ref([]);
const locations = ref([]);
const genders = ref([
    {data: 'male', label: 'Male'},
    {data: 'female', label: 'Female'},
    {data: 'other', label: 'Other'},
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
    const speaker = RecordStore.data.metadata.speaker;
    if (!speaker.dialect_id || !speaker.location_id || !speaker.gender) {
        alert('Please fill out all the fields.');
        return false;
    }
    return true;
}

onMounted(async () => {
    await RecordStore.fetchSpeaker();
    await fetchSpeakerOptions();
});
</script>

<template>
    <div class="wizard-page-title">
        <h2>Speaker</h2>
    </div>
    <div class="tip">
        <div class="material-symbols-rounded">info</div>
        <div class="tip-content">
            <p>Every <b>Recording</b> belongs to a <b>Speaker</b> — that's you! Fill in the fields below to let others
                know the dialect & other sociolinguistic information behind what they're hearing.</p>
        </div>
    </div>

    <!--    Make sure you can't proceed if you don't have a Speaker profile created yet. -->

    <div class="wizard-section-container">
        <section>
            <div class="user-avatar">
                <img alt="Profile Picture"
                     src="/img/avatars/battix01.jpg"/>
            </div>
            <div class="user-name">{{ RecordStore.data.metadata.speaker.name ?? 'Loading...' }}</div>
        </section>
        <section>

            <div class="mwe-rw-field">
                <label for="dialect">Dialect</label>
                <WizardDropdown
                    id="dialect"
                    :options="dialects.map(dialect => ({ data: dialect.id, label: dialect.name }))"
                    v-model="RecordStore.data.metadata.speaker.dialect_id"
                />
            </div>
            <div class="mwe-rw-field">
                <label for="location">Location</label>
                <WizardDropdown
                    id="location"
                    :options="locations.map(location => ({ data: location.id, label: location.name }))"
                    v-model="RecordStore.data.metadata.speaker.location_id"
                />
            </div>
            <div class="mwe-rw-field">
                <label for="gender">Gender</label>
                <WizardSelect
                    id="gender"
                    :options="genders"
                    v-model="RecordStore.data.metadata.speaker.gender"
                />
            </div>

            <WizardButton
                :label="RecordStore.data.metadata.speaker.user_id ? 'Update Speaker Profile' : 'Create Speaker Profile'"
                @click="() => { if (validateForm()) RecordStore.saveSpeaker(); }"
            />
        </section>
    </div>
</template>
