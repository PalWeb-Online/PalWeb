<script setup>
import {computed, onMounted, ref, watch} from "vue";
import draggable from 'vuedraggable';
import SentenceItem from "../ui/SentenceItem.vue";
import TermItem from "../ui/TermItem.vue";
import {useSearchStore} from "../../../../stores/SearchStore.js";
import {useNotificationStore} from "../../../../stores/NotificationStore.js";
import {useNavGuard} from "../../../../composables/NavGuard.js";
import AppButton from "../../../../components/AppButton.vue";
import AppTip from "../../../../components/AppTip.vue";
import {useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import NavGuard from "../../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../../components/Modals/ModalWrapper.vue";

const props = defineProps({
    dialog: Object,
    sentence: Object,
});

const SearchStore = useSearchStore();
const NotificationStore = useNotificationStore();

const sentence = useForm({
    id: props.sentence?.id || null,
    sentence: props.sentence?.sentence || '',
    translit: props.sentence?.translit || '',
    trans: props.sentence?.trans || '',
    dialog: props.sentence?.dialog || props.dialog || {},
    speaker: props.sentence?.speaker || '',
    position: props.sentence?.position || '',
    terms: props.sentence?.terms || [],
});

const termsList = ref([]);

const isSaving = ref(false);

const hasNavigationGuard = computed(() => {
    return sentence.isDirty && !isSaving.value;
});

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

const insertTerm = (term) => {
    const newTerm = {
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
    };

    termsList.value.push({
        ...newTerm,
        uuid: crypto.randomUUID(),
    });

    NotificationStore.addNotification(`Added ${term.term} to the Sentence!`);
}

const addTerm = () => {
    const newTerm = {
        sentencePivot: {
            sent_term: '',
            sent_translit: '',
            position: '',
        }
    };

    termsList.value.push({
        ...newTerm,
        uuid: crypto.randomUUID(),
    });
}

const removeTerm = (index) => {
    termsList.value.splice(index, 1);
}

const isValidRequest = computed(() => {
    if (!sentence.trans) return false;
    if (sentence.terms.length === 0) return false;
    if (!!props.dialog && !sentence.speaker) return false;

    for (const term of sentence.terms) {
        if (!term.sentencePivot.sent_term || !term.sentencePivot.sent_translit) {
            return false;
        }
    }

    return true;
});

const saveSentence = async () => {
    isSaving.value = true;

    const method = sentence.id
        ? sentence.patch.bind(sentence)
        : sentence.post.bind(sentence);

    const url = sentence.id
        ? route('sentences.update', sentence.id)
        : route('sentences.store');

    method(url, {
        onSuccess: () => {
            NotificationStore.addNotification('The Sentence has been saved!');
            sentence.defaults();
            isSaving.value = false;
        },
        onError: () => {
            NotificationStore.addNotification('Oh no! The Deck could not be saved.');
            isSaving.value = false;
        }
    });
};

onMounted(() => {
    termsList.value = (props.sentence?.terms ?? []).map((term) => {
        return {
            ...term,
            uuid: crypto.randomUUID(),
        };
    });

    if (!!props.dialog) {
        sentence.position = props.dialog.sentences_count + 1;
    }

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

watch(
    () => props.sentence,
    (newValue) => {
        if (newValue) {
            Object.assign(sentence, newValue);
        }
    },
    {deep: true}
);

watch(
    termsList,
    (newTermsList) => {
        newTermsList.forEach((term, index) => {
            term.sentencePivot.position = index + 1;
        });

        while (sentence.terms.length > newTermsList.length) {
            sentence.terms.pop();
        }

        newTermsList.forEach((term, index) => {
            const { uuid, ...newTerm } = term;

            if (sentence.terms[index]) {
                Object.assign(sentence.terms[index], newTerm);
            } else {
                sentence.terms.push(newTerm);
            }
        });
    },
    { deep: true }
);
</script>

<template>
    <div class="app-nav-interact">
        <div class="app-nav-interact-buttons">
            <AppButton :disabled="sentence.processing || !hasNavigationGuard || !isValidRequest" label="Save"
                       @click="saveSentence"
            />
            <AppButton :disabled="sentence.processing || !hasNavigationGuard" label="Reset"
                       @click="sentence.reset()"
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
        <SentenceItem :sentence="sentence" page="sentence" :inDialog="!!dialog"/>
        <draggable :list="termsList" itemKey="uuid"
                   @end="updatePosition()"
                   class="draggable">
            <template #item="{ element, index }">
                <div class="draggable-item">
                    <TermItem :term="element"/>
                    <img src="/img/trash.svg" class="trash" alt="Delete"
                         v-show="termsList.length > 0"
                         @click="removeTerm(index)"/>
                </div>
            </template>
        </draggable>
    </div>

    <ModalWrapper v-model="showAlert">
        <NavGuard
            v-if="showAlert"
            message="You have unsaved changes. Are you sure you want to leave this page? Unsaved changes will be lost."
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>
</template>
