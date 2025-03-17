<script setup>
import {computed, onMounted, reactive, ref, watch} from "vue";
import draggable from 'vuedraggable';
import SentenceItem from "../ui/SentenceItem.vue";
import {useSearchStore} from "../../../../stores/SearchStore.js";
import {useNotificationStore} from "../../../../stores/NotificationStore.js";
import {useNavGuard} from "../../../../composables/NavGuard.js";
import AppButton from "../../../../components/AppButton.vue";
import DialogActions from "../../../../components/DialogActions.vue";
import AppAlert from "../../../../components/AppAlert.vue";
import {cloneDeep, isEqual} from "lodash";
import {Inertia} from "@inertiajs/inertia";
import {route} from "ziggy-js";

const props = defineProps({
    dialog: Object,
});

const SearchStore = useSearchStore();
const NotificationStore = useNotificationStore();

const stagedDialog = reactive({
    id: null,
    title: '',
    description: '',
    media: '',
    sentences: [],
});

const originalDialog = ref(null);

const resetDialog = () => {
    Object.assign(stagedDialog, cloneDeep(originalDialog.value));
}

const isSaving = ref(false);

const hasNavigationGuard = computed(() => {
    return !isEqual(stagedDialog, originalDialog.value) && !isSaving.value;
});

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

const insertSentence = async (sentence) => {
    // todo: check if the Sentence already exists; see Build page

    const response = await axios.get(route('speech-maker.get-terms', sentence.id));

    stagedDialog.sentences.push({
        id: sentence.id,
        sentence: sentence.sentence,
        translit: sentence.translit,
        trans: sentence.trans,
        terms: response.data.terms,
    });

    updatePosition();
    NotificationStore.addNotification(`Added ${sentence.sentence} to the Dialog!`);
}

const removeSentence = (index) => {
    stagedDialog.sentences.splice(index, 1);
    updatePosition();
}

const updatePosition = () => {
    stagedDialog.sentences.forEach((sentence, index) => {
        sentence.position = index + 1;
    });
}

const isValidRequest = computed(() => {
    if (!stagedDialog.title) return false;

    for (const sentence of stagedDialog.sentences) {
        if (!sentence.speaker || !sentence.position) {
            return false;
        }
    }

    return true;
});

const saveDialog = async () => {
    isSaving.value = true;

    const method = stagedDialog.id
        ? Inertia.patch.bind(Inertia)
        : Inertia.post.bind(Inertia);

    const url = stagedDialog.id
        ? route('dialogs.update', stagedDialog.id)
        : route('dialogs.store');

    method(url, {dialog: stagedDialog},
        {
            onSuccess: () => {
                NotificationStore.addNotification('The Dialog has been saved!');
                originalDialog.value = cloneDeep(stagedDialog);
                isSaving.value = false;
            },
            onError: () => {
                NotificationStore.addNotification('Oh no! The Dialog could not be saved.');
                isSaving.value = false;
            }
        }
    );

};

watch(
    () => props.dialog,
    (newValue) => {
        if (newValue) {
            Object.assign(stagedDialog, newValue);
        }
    },
    {deep: true}
);

onMounted(() => {
    if (!!props.dialog) {
        Object.assign(stagedDialog, props.dialog);
    }

    originalDialog.value = cloneDeep(stagedDialog);

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
                       :disabled="isSaving || !hasNavigationGuard || !isValidRequest"
            />
            <AppButton @click="resetDialog" label="Reset"
                       :disabled="isSaving || !hasNavigationGuard"
            />
            <AppButton @click="SearchStore.openSearchGenie('insert', 'sentences')"
                       label="Insert Sentence"
            />
            <AppButton @click="Inertia.get(route('speech-maker.dialog-sentence', stagedDialog.id))"
                       :disabled="!stagedDialog.id" label="New Sentence"
            />
        </div>
    </div>

    <div class="dialog-container">
        <div class="dialog-container-head">
            <input class="dialog-container-head-title" v-model="stagedDialog.title"
                   placeholder="Required: Dialog Title"
            />
            <DialogActions v-if="stagedDialog.id" :model="stagedDialog"/>
        </div>
        <input v-model="stagedDialog.media"/>
        <textarea class="dialog-description" v-model="stagedDialog.description"/>

        <div class="dialog-body">
            <draggable :list="stagedDialog.sentences" itemKey="id"
                       @end="updatePosition()"
                       class="draggable">
                <template #item="{ element, index }">
                    <div class="draggable-item">
                        <SentenceItem :sentence="element" page="dialog"/>
                        <img src="/img/trash.svg" class="trash" alt="Delete"
                             v-show="stagedDialog.sentences.length > 0"
                             @click="removeSentence(index)"/>
                    </div>
                </template>
            </draggable>
        </div>
    </div>

    <AppAlert
        v-if="showAlert"
        message="You have unsaved changes. Are you sure you want to leave this page? Unsaved changes will be lost."
        @confirm="handleConfirm"
        @cancel="handleCancel"
    />
</template>
