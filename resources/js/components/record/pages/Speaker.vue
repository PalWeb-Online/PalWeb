<script setup>
import { ref, reactive, onMounted, watch, computed } from 'vue';
import WizardDropdown from '../ui/WizardDropdown.vue';
import WizardText from '../ui/WizardTextInput.vue';
import WizardSelect from '../ui/WizardSelect.vue';

// Data and configurations
const profiles = ref([]);
const metadata = reactive({
    speaker: {
        qid: '',
        name: '',
        gender: '',
        languages: {},
        location: '',
        new: false,  // To manage new speaker creation
    },
    license: '',
});
const genderOptions = ref([
    { data: 'Q6581097', label: 'Male' },  // QIDs for gender in Wikibase
    { data: 'Q6581072', label: 'Female' },
    { data: 'Q48270', label: 'Other' }
]);
const availableLanguages = ref([]);
const availableLicenses = ref([]);

const config = reactive({
    speaker: { name: 'Default Name', qid: 'Q12345' },  // Mock speaker QID
    otherSpeakers: {},  // To manage additional speaker profiles
    licenses: []  // To be populated dynamically
});

// License text
const licenseText = computed(() => {
    return `By contributing, you agree to release your recordings under the selected license for speaker ${metadata.speaker.name}.`;
});

// Fetch and build the profiles, languages, and licenses dynamically on component creation
onMounted(() => {
    // Fill profiles with speaker data
    profiles.value.push({ optgroup: 'Main Profile' });
    profiles.value.push({ data: config.speaker.qid || '*', label: config.speaker.name });
    profiles.value.push({ optgroup: 'Other Profiles' });
    for (const qid in config.otherSpeakers) {
        profiles.value.push({ data: qid, label: config.otherSpeakers[qid].name });
    }
    profiles.value.push({ data: '+', label: 'Create New Profile' });

    // Set available languages (mocking for now)
    availableLanguages.value = [
        { data: 'Q1860', label: 'English' },
        { data: 'Q150', label: 'French' },
        { data: 'Q13955', label: 'Arabic' }
    ];

    // Populate licenses from config
    buildLicenses(config.licenses);
});

// Watch for changes in the speaker profile to update the metadata
watch(() => metadata.speaker.qid, (newQid) => {
    if (newQid === config.speaker.qid || newQid === '*') {
        setSpeaker(config.speaker);
    } else if (newQid[0] === 'Q') {
        setSpeaker(config.otherSpeakers[newQid]);
    } else {
        setSpeaker({ qid: '+', new: true });
    }
});

// Methods for managing speakers, licenses, and errors
const setSpeaker = (speakerData) => {
    Object.assign(metadata.speaker, speakerData);
};

const buildLicenses = (licenseData) => {
    // Populate availableLicenses based on the licenses passed from config
    availableLicenses.value = licenseData.map(license => ({
        label: license.text,
        data: license.template
    }));
};

const validateSpeaker = () => {
    if (metadata.speaker.name === '') {
        alert('Error: The speaker must have a name.');
        return false;
    }
    if (Object.keys(metadata.speaker.languages).length === 0) {
        alert('Error: Please select at least one language.');
        return false;
    }
    return true;
};
</script>

<template>
    <div>
        <div class="mwe-rw-content-title">
            <h3>Speaker Information</h3>
        </div>
        <div class="mwe-rw-section-group">
            <section class="mwe-rw-warning">
                Please review your speaker profile carefully before continuing.
            </section>
            <section>
                <!-- Speaker Profile -->
                <div id="mwe-rwl-profile-field" class="mwe-rw-field">
                    <label for="mwe-rwl-profile-input">Speaker Profile</label>
                    <WizardDropdown
                        id="mwe-rwl-profile"
                        input-id="mwe-rwl-profile-input"
                        :options="profiles"
                        v-model="metadata.speaker.qid"
                    />
                </div>

                <!-- Speaker Name -->
                <div id="mwe-rwl-name-field" class="mwe-rw-field">
                    <label for="mwe-rwl-name-input">Name</label>
                    <WizardText
                        id="mwe-rwl-name"
                        input-id="mwe-rwl-name-input"
                        v-model="metadata.speaker.name"
                        :disabled="metadata.speaker.name === config.speaker.name"
                    />
                </div>

                <!-- Gender Selection -->
                <div id="mwe-rwl-gender-field" class="mwe-rw-field">
                    <label for="mwe-rwl-gender-input">Gender</label>
                    <WizardSelect
                        id="mwe-rwl-gender"
                        input-id="mwe-rwl-gender-input"
                        :options="genderOptions"
                        v-model="metadata.speaker.gender"
                    />
                </div>

                <!-- Speaker Languages -->
                <div id="mwe-rwl-languages-field" class="mwe-rw-field">
                    <label for="mwe-rwl-languages-input">Languages</label>
                    <ll-langselector
                        id="mwe-rwl-languages"
                        input-id="mwe-rwl-languages-input"
                        :options="availableLanguages"
                        v-model="metadata.speaker.languages"
                    />
                </div>

                <!-- Location -->
                <div id="mwe-rwl-location-field" class="mwe-rw-field">
                    <label for="mwe-rwl-location-input">Location</label>
                    <ll-wdsearch
                        id="mwe-rwl-location"
                        input-id="mwe-rwl-location-input"
                        v-model="metadata.speaker.location"
                    />
                </div>

                <!-- License Agreement -->
                <div id="mwe-rwl-license-field" class="mwe-rw-field">
                    <label for="mwe-rwl-license-input">License</label>
                    <div>
                        <span v-html="licenseText"></span>
                        <WizardDropdown
                            id="mwe-rwl-license"
                            input-id="mwe-rwl-license-input"
                            :options="availableLicenses"
                            v-model="metadata.license"
                        />
                        I agree to release my recordings under the selected license.
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<style scoped>
.mwe-rw-content-title {
    margin-bottom: 20px;
}

.mwe-rw-field {
    margin-bottom: 15px;
}

.mwe-rw-warning {
    color: red;
    font-weight: bold;
    margin-bottom: 15px;
}
</style>
