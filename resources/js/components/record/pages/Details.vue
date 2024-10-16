<script setup>
import {onMounted, ref, watch} from 'vue';
import useRecordStore from '../store/useRecordStore'; // Assuming we refactored store here
import useConfigStore from '../store/useConfigStore'; // For fetching past records
import WizardText from '../ui/WizardTextInput.vue';
import WizardButton from '../ui/WizardButton.vue';
import WizardDropdown from '../ui/WizardDropdown.vue';
import WizardCheckbox from '../ui/WizardCheckbox.vue';
import Draggable from 'vuedraggable';

// Import stores
const recordStore = useRecordStore();
const configStore = useConfigStore();

// Reactive data
const wordInputed = ref('');
const words = ref(recordStore.words); // Use the store's word list
const availableLanguages = ref([]);
const randomise = ref(false);
const metadata = ref(recordStore.metadata);

// Methods
const addWordFromInput = () => {
    if (wordInputed.value.trim()) {
        const wordsArray = wordInputed.value.split('#').map(word => word.trim());
        recordStore.addWords(wordsArray); // Use store method to add words
        wordInputed.value = ''; // Clear input after adding
    }
};

const clear = (index) => {
    recordStore.clearRecord(words.value[index]); // Remove from store
};

const clearAll = () => {
    recordStore.clearAllRecords(); // Clear all records in store
};

const deduplicate = () => {
    const pastRecords = configStore.getPastRecords(metadata.value.language);
    recordStore.words = recordStore.words.filter(word => !pastRecords.includes(word)); // Deduplicate words
};

const onLanguageChange = () => {
    clearAll();
    setupCurrentLanguage();
};

const setupCurrentLanguage = () => {
    // Update media type based on the selected language
    recordStore.updateMediaType();

    // Fetch past records asynchronously for the current language
    configStore.fetchPastRecords(metadata.value.language, metadata.value.speaker.qid);
};

// When the component is mounted, set up available languages and watch the current step
onMounted(() => {
    availableLanguages.value = Object.keys(metadata.value.speaker.languages).map(qid => ({
        data: qid,
        label: configStore.getLanguageLabel(qid),
    }));

    if (!metadata.value.language || !metadata.value.speaker.languages[metadata.value.language]) {
        metadata.value.language = availableLanguages.value[0]?.data || 'en';
    }

    setupCurrentLanguage();
});

// Watch for step change (if controlled externally)
watch(() => recordStore.state.step, (newStep) => {
    if (newStep === 'details') {
        setupCurrentLanguage();
    }
});
</script>

<template>
    <div class="wizard-page-title">
        <h2>Details</h2>
    </div>

    <div class="mwe-rw-section-group">
        <section>
            <!-- Word Input Field -->
            <div id="mwe-rwd-input">
                <WizardText
                    id="mwe-rwd-word"
                    input-id="mwe-rwd-word-input"
                    v-model="wordInputed"
                    @enter="addWordFromInput"
                    placeholder="Enter word or phrase"
                />
                <WizardButton
                    id="mwe-rwd-add"
                    :invisible-label="true"
                    icon="add"
                    flags="primary progressive"
                    @click="addWordFromInput"
                />
                <div id="mwe-rwd-input-help">
                    Enter words or phrases that you would like to record.
                </div>
            </div>

            <!-- Draggable List -->
            <Draggable v-model="words" id="mwe-rwd-list">
                <template #item="{ element, index }">
                    <li class="draggable-item">
                        <span>{{ element }}</span>
                        <i class="mwe-rws-again" @click="clear(index)"></i>
                    </li>
                </template>
            </Draggable>

            <!-- Action Buttons -->
            <div id="mwe-rwd-actions">
                <WizardButton
                    id="mwe-rwd-deduplicate"
                    label="Deduplicate"
                    @click="deduplicate"
                />
                <WizardButton
                    id="mwe-rwd-clear"
                    label="Clear All"
                    flags="destructive"
                    @click="clearAll"
                />
            </div>
        </section>

        <section>
            <!-- Language Selection -->
            <div id="mwe-rwd-lang-field">
                <label for="mwe-rwd-lang-input">Language</label>
                <WizardDropdown
                    id="mwe-rwd-lang"
                    input-id="mwe-rwd-lang-input"
                    :options="availableLanguages"
                    v-model="metadata.language"
                    @change="onLanguageChange"
                />
                <WizardCheckbox
                    id="mwe-rwd-randomise"
                    v-model="randomise"
                />
                Randomise order
            </div>

            <!-- Generators Section -->
            <div id="mwe-rwd-generators-field">
                <h4>Inspiration</h4>
                Use these generators for inspiration:
                <div id="mwe-rwd-generator-buttons">
                    <WizardButton
                        v-for="generator in generators"
                        :key="generator.name"
                        :id="'mwe-rwd-generator-' + generator.name"
                        :label="generator.title"
                        :icon="generator.icon"
                        @click="$windowManager.openWindow(generator.name)"
                    />
                </div>
            </div>
        </section>
    </div>
</template>
