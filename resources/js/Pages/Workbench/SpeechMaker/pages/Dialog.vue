<script setup>
import {computed, onMounted, ref, watch} from "vue";
import draggable from 'vuedraggable';
import SentenceItem from "../ui/SentenceItem.vue";
import {useSearchStore} from "../../../../stores/SearchStore.js";
import {useNotificationStore} from "../../../../stores/NotificationStore.js";
import {useNavGuard} from "../../../../composables/NavGuard.js";
import AppButton from "../../../../components/AppButton.vue";
import DialogActions from "../../../../components/DialogActions.vue";
import {route} from "ziggy-js";
import {router, useForm} from "@inertiajs/vue3";
import NavGuard from "../../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../../components/Modals/ModalWrapper.vue";

const props = defineProps({
    dialog: Object,
});

const SearchStore = useSearchStore();
const NotificationStore = useNotificationStore();

const dialog = useForm({
    id: props.dialog?.id || null,
    title: props.dialog?.title || '',
    description: props.dialog?.description || '',
    media: props.dialog?.media || '',
    sentences: props.dialog?.sentences || [],
});

const isSaving = ref(false);

const hasNavigationGuard = computed(() => {
    return dialog.isDirty && !isSaving.value;
});

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

const insertSentence = async (sentence) => {
    const sentenceExists = dialog.sentences.some(existingSentence => existingSentence.id === sentence.id);

    if (sentenceExists) {
        NotificationStore.addNotification('This Sentence is already in the Dialog!', 'error');

    } else if (sentence.dialog) {
        NotificationStore.addNotification('This Sentence is already in a Dialog!', 'error');

    } else {
        const response = await axios.get(route('speech-maker.get-terms', sentence.id));

        dialog.sentences.push({
            id: sentence.id,
            sentence: sentence.sentence,
            translit: sentence.translit,
            trans: sentence.trans,
            terms: response.data.terms,
        });

        updatePosition();
        NotificationStore.addNotification(`Added ${sentence.sentence} to the Dialog!`);
    }
}

const removeSentence = (index) => {
    dialog.sentences.splice(index, 1);
    updatePosition();
}

const updatePosition = () => {
    dialog.sentences.forEach((sentence, index) => {
        sentence.position = index + 1;
    });
}

const isValidRequest = computed(() => {
    if (!dialog.title) return false;

    for (const sentence of dialog.sentences) {
        if (!sentence.speaker || !sentence.position) {
            return false;
        }
    }

    return true;
});

const saveDialog = async () => {
    isSaving.value = true;

    const method = dialog.id
        ? dialog.patch.bind(dialog)
        : dialog.post.bind(dialog);

    const url = dialog.id
        ? route('dialogs.update', dialog.id)
        : route('dialogs.store');

    method(url, {
        onSuccess: () => {
            NotificationStore.addNotification('The Dialog has been saved!');
            dialog.defaults();
            isSaving.value = false;
        },
        onError: () => {
            NotificationStore.addNotification('Oh no! The Dialog could not be saved.');
            isSaving.value = false;
        }
    });
};

watch(
    () => props.dialog,
    (newValue) => {
        if (newValue) {
            Object.assign(dialog, newValue);
        }
    },
    {deep: true}
);

onMounted(() => {
    watch(
        () => SearchStore.data.selectedModel,
        (newModel) => {
            if (newModel) {
                insertSentence(newModel);
                SearchStore.deselectModel();
            }
        }
    );
});
</script>

<template>
    <div class="app-nav-interact">
        <div class="app-nav-interact-buttons">
            <AppButton @click="saveDialog" label="Save"
                       :disabled="dialog.processing || !hasNavigationGuard || !isValidRequest"
            />
            <AppButton @click="dialog.reset()" label="Reset"
                       :disabled="dialog.processing || !hasNavigationGuard"
            />
            <AppButton @click="SearchStore.openSearchGenie('insert', 'sentences')"
                       label="Insert Sentence"
            />
            <AppButton @click="router.get(route('speech-maker.dialog-sentence', dialog.id))"
                       :disabled="!dialog.id" label="New Sentence"
            />
        </div>
    </div>

    <div class="dialog-container">
        <div class="dialog-container-head">
            <input class="dialog-container-head-title" v-model="dialog.title"
                   placeholder="Required: Dialog Title"
            />
            <DialogActions v-if="dialog.id" :model="dialog"/>
        </div>
        <input v-model="dialog.media"/>
        <textarea class="dialog-description" v-model="dialog.description"/>

        <div class="dialog-body">
            <draggable :list="dialog.sentences" itemKey="id"
                       @end="updatePosition()"
                       class="draggable">
                <template #item="{ element, index }">
                    <div class="draggable-item">
                        <SentenceItem :sentence="element" page="dialog" inDialog/>
                        <img src="/img/trash.svg" class="trash" alt="Delete"
                             v-show="dialog.sentences.length > 0"
                             @click="removeSentence(index)"/>
                    </div>
                </template>
            </draggable>
        </div>
    </div>

    <ModalWrapper v-model="showAlert">
        <NavGuard
            message="You have unsaved changes. Are you sure you want to leave this page? Unsaved changes will be lost."
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>
</template>
