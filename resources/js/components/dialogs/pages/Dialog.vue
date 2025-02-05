<script setup>
import {onMounted} from "vue";
import {useSearchStore} from "../../../stores/SearchStore.js";
import {useNotificationStore} from "../../../stores/NotificationStore.js";
import {useStateStore} from "../stores/StateStore.js";
import {useDialogStore} from "../stores/DialogStore.js";
import {useSentenceStore} from "../stores/SentenceStore.js";
import draggable from 'vuedraggable';
import AppButton from "../../AppButton.vue";
import SentenceItem from "../ui/SentenceItem.vue";
import DialogActions from "../../DialogActions.vue";
import AppAlert from "../../AppAlert.vue";
import {useNavGuard} from "../../../composables/NavGuard.js";

const SearchStore = useSearchStore();
const NotificationStore = useNotificationStore();
const StateStore = useStateStore();
const DialogStore = useDialogStore();
const SentenceStore = useSentenceStore();

const {showAlert, handleConfirm, handleCancel} = useNavGuard(StateStore);

const insertSentence = (sentence) => {
    DialogStore.data.stagedDialog.sentences.push({
        id: sentence.id,
        sentence: sentence.sentence,
        translit: sentence.translit,
        trans: sentence.trans,
        terms: sentence.terms,
    });
    updatePosition();
    NotificationStore.addNotification(`Added ${sentence.sentence} to the Dialog!`);
}

const addSentence = () => {
    const newSentence = {
        id: null,
        sentence: '',
        translit: '',
        trans: '',
        terms: [],
        dialog_id: null,
        speaker: '',
        position: ''
    };

    DialogStore.data.stagedDialog.sentences.push(newSentence);
    updatePosition();

    SentenceStore.selectSentence(DialogStore.data.stagedDialog.sentences.length - 1);
}

const removeSentence = (index) => {
    if (DialogStore.data.stagedDialog.sentences[index].id === SentenceStore.data.stagedSentence.id) {
        SentenceStore.data.stagedSentence = {
            id: null,
            sentence: '',
            translit: '',
            trans: '',
            terms: [],
        };
    }

    DialogStore.data.stagedDialog.sentences.splice(index, 1);
    updatePosition();
}

const updatePosition = () => {
    DialogStore.data.stagedDialog.sentences.forEach((sentence, index) => {
        sentence.position = index + 1;
    });
}

const saveDialog = async () => {
    const success = await DialogStore.saveDialog();

    if (success) {
        NotificationStore.addNotification('The Dialog has been saved!');
    } else {
        NotificationStore.addNotification(StateStore.data.errorMessage, 'error');
    }
};

onMounted(async () => {
    SearchStore.context = 'dialogger';
    DialogStore.data.stagedDialog.sentences.forEach((sentence, index) => {
        !sentence.id && DialogStore.data.stagedDialog.sentences.splice(index, 1);
    });
});
</script>

<template>
    <div class="app-nav-interact">
        <div class="app-nav-interact-buttons">
            <AppButton :disabled="!StateStore.hasNavigationGuard || !StateStore.isValidRequest" label="Save"
                       @click="saveDialog"
            />
            <AppButton :disabled="!StateStore.hasNavigationGuard" label="Reset"
                       @click="DialogStore.resetDialog"
            />
            <AppButton label="Add Sentence" @click="addSentence"/>
        </div>
    </div>

    <div class="dialog-container">
        <div class="dialog-container-head">
            <input class="dialog-container-head-title" v-model="DialogStore.data.stagedDialog.title"
                   placeholder="Required: Dialog Title"
            />
            <DialogActions v-if="DialogStore.data.stagedDialog.id" :model="DialogStore.data.stagedDialog"/>
        </div>
        <input v-model="DialogStore.data.stagedDialog.media"/>
        <textarea class="dialog-description" v-model="DialogStore.data.stagedDialog.description"/>

        <div class="dialog-body">
            <draggable :list="DialogStore.data.stagedDialog.sentences" itemKey="id"
                       @end="updatePosition()"
                       class="draggable">
                <template #item="{ element, index }">
                    <div class="db-item">
                        <img src="/img/play.svg" class="play" @click="SentenceStore.selectSentence(index)" alt="Next"/>
                        <SentenceItem :sentence="element" speaker/>
                        <img src="/img/trash.svg" class="trash" alt="Delete"
                             v-show="DialogStore.data.stagedDialog.sentences.length > 0"
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
