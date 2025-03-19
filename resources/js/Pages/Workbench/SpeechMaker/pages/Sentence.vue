<script setup>
import {computed, onMounted, reactive, ref, watch} from "vue";
import {cloneDeep, isEqual} from "lodash";
import draggable from 'vuedraggable';
import SentenceItem from "../ui/SentenceItem.vue";
import TermItem from "../ui/TermItem.vue";
import {useSearchStore} from "../../../../stores/SearchStore.js";
import {useNotificationStore} from "../../../../stores/NotificationStore.js";
import {useNavGuard} from "../../../../composables/NavGuard.js";
import AppButton from "../../../../components/AppButton.vue";
import AppAlert from "../../../../components/AppAlert.vue";
import AppTip from "../../../../components/AppTip.vue";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";

const props = defineProps({
    dialog: Object,
    sentence: Object,
});

const SearchStore = useSearchStore();
const NotificationStore = useNotificationStore();

const stagedSentence = reactive({
    id: null,
    sentence: '',
    translit: '',
    trans: '',
    dialog: {},
    speaker: '',
    position: '',
    terms: [],
});

const originalSentence = ref(null);

const resetSentence = () => {
    Object.assign(stagedSentence, cloneDeep(originalSentence.value));
};

const isSaving = ref(false);

const hasNavigationGuard = computed(() => {
    return !isEqual(stagedSentence, originalSentence.value) && !isSaving.value;
});

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

const insertTerm = (term) => {
    stagedSentence.terms.push({
        id: term.id,
        term: term.term,
        category: term.category,
        translit: term.translit,
        glosses: term.glosses.map((gloss) => ({
            id: gloss.id,
            gloss: gloss.gloss,
        })),
        sentencePivot: {
            gloss_id: term.glosses[0].id,
            sent_term: term.term,
            sent_translit: term.translit,
            position: '',
        }
    });
    updatePosition();
    NotificationStore.addNotification(`Added ${term.term} to the Sentence!`);
}

const addTerm = () => {
    stagedSentence.terms.push({
        sentencePivot: {
            sent_term: '',
            sent_translit: '',
            position: '',
        }
    });
    updatePosition();
}

const removeTerm = (index) => {
    stagedSentence.terms.splice(index, 1);
    updatePosition();
}

const updatePosition = () => {
    stagedSentence.terms.forEach((term, index) => {
        term.sentencePivot.position = index + 1;
    });
}

const isValidRequest = computed(() => {
    if (!stagedSentence.trans) return false;
    if (stagedSentence.terms.length === 0) return false;
    if (!!props.dialog && !stagedSentence.speaker) return false;

    for (const term of stagedSentence.terms) {
        if (!term.sentencePivot.sent_term || !term.sentencePivot.sent_translit) {
            return false;
        }
    }

    return true;
});

const saveSentence = async () => {
    isSaving.value = true;

    const method = stagedSentence.id
        ? router.patch.bind(router)
        : router.post.bind(router);

    const url = stagedSentence.id
        ? route('sentences.update', stagedSentence.id)
        : route('sentences.store');

    method(url, {sentence: stagedSentence},
        {
            onSuccess: () => {
                NotificationStore.addNotification('The Sentence has been saved!');
                originalSentence.value = cloneDeep(stagedSentence);
                isSaving.value = false;

                if (!!props.dialog) {
                    router.visit(route('speech-maker.dialog', props.dialog.id));
                }
            },
            onError: () => {
                NotificationStore.addNotification('Oh no! The Deck could not be saved.');
                isSaving.value = false;
            }
        }
    );
};

watch(
    () => props.sentence,
    (newValue) => {
        if (newValue) {
            Object.assign(stagedSentence, newValue);
        }
    },
    {deep: true}
);

onMounted(() => {
    if (!!props.sentence) {
        Object.assign(stagedSentence, props.sentence);
    }

    if (!!props.dialog) {
        Object.assign(stagedSentence.dialog, props.dialog);
        stagedSentence.position = stagedSentence.dialog.sentences_count + 1;
    }

    originalSentence.value = cloneDeep(stagedSentence);

    watch(
        () => SearchStore.data.selectedModel,
        (newModel) => {
            if (newModel) {
                insertTerm(newModel);
                SearchStore.deselectModel();
            }
        }
    );
});
</script>

<template>
    <div class="app-nav-interact">
        <div class="app-nav-interact-buttons">
            <AppButton :disabled="!hasNavigationGuard || !isValidRequest" label="Save"
                       @click="saveSentence"
            />
            <AppButton :disabled="!hasNavigationGuard" label="Reset"
                       @click="resetSentence"
            />
            <AppButton @click="SearchStore.openSearchGenie('insert', 'terms')"
                       label="Insert Term"
            />
            <AppButton @click="addTerm" label="Blank Term"/>
        </div>
    </div>

    <AppTip v-if="!!dialog">
        <p>Creating a Sentence within the Dialog (<b>{{ dialog.title }}</b>). It will be created
            & added to the Dialog on Save.
        </p>
    </AppTip>

    <div class="sentence-container">
        <SentenceItem :sentence="stagedSentence" page="sentence" :inDialog="!!dialog"/>
        <draggable :list="stagedSentence.terms" itemKey="id"
                   @end="updatePosition()"
                   class="draggable">
            <template #item="{ element, index }">
                <div class="draggable-item">
                    <TermItem :term="element"/>
                    <img src="/img/trash.svg" class="trash" alt="Delete"
                         v-show="stagedSentence.terms.length > 0"
                         @click="removeTerm(index)"/>
                </div>
            </template>
        </draggable>
    </div>

    <AppAlert
        v-if="showAlert"
        message="You have unsaved changes. Are you sure you want to leave this page? Unsaved changes will be lost."
        @confirm="handleConfirm"
        @cancel="handleCancel"
    />
</template>
