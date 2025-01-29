<script setup>
import {onMounted, ref} from "vue";
import {useStateStore} from "../stores/StateStore.js";
import {useDialogStore} from "../stores/DialogStore.js";
import {useSentenceStore} from "../stores/SentenceStore.js";
import draggable from 'vuedraggable';
import AppNotification from "../../AppNotification.vue";
import AppButton from "../../AppButton.vue";
import DialogLine from "../ui/DialogLine.vue";
import SearchGenie from "../../search/SearchGenie.vue";

const StateStore = useStateStore();
const DialogStore = useDialogStore();
const SentenceStore = useSentenceStore();
const notification = ref(null);

const insertSentence = (sentence) => {
    DialogStore.data.stagedDialog.sentences.push({
        id: sentence.id,
        sentence: sentence.sentence,
        translit: sentence.translit,
        trans: sentence.trans,
        terms: sentence.terms,
    });
    updatePosition();
    notification.value.showNotification(`Added ${sentence.sentence} to the Dialog!`);
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

    SentenceStore.toggleSelectSentence(DialogStore.data.stagedDialog.sentences.length - 1);
    StateStore.data.step = 'sentence';
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
        notification.value.showNotification('The Dialog has been saved!');
    } else {
        notification.value.showNotification(StateStore.data.errorMessage, 'error');
    }
};

const deleteDialog = async () => {
    const success = await DialogStore.deleteDialog();

    if (success) {
        notification.value.showNotification('The Dialog has been deleted!');

        setTimeout(() => {
            window.location.href = '/academy/dialogs';
        }, 1000);

    } else {
        notification.value.showNotification(StateStore.data.errorMessage, 'error');
    }
};

onMounted(async () => {
    DialogStore.data.stagedDialog.sentences.forEach((sentence, index) => {
        !sentence.id && DialogStore.data.stagedDialog.sentences.splice(index, 1);
    });
});
</script>

<template>
    <SearchGenie :context="'dialogger'" @emitSentence="insertSentence($event)"/>

    <div class="db-build-buttons">
        <AppButton :disabled="!StateStore.hasUnsavedChanges || !StateStore.isValidRequest" label="Save"
                   @click="saveDialog"
        />
        <AppButton :disabled="!StateStore.hasUnsavedChanges" label="Reset"
                   @click="DialogStore.resetDialog"
        />
        <AppButton :disabled="!DialogStore.data.stagedDialog.id" label="View"
                   @click="DialogStore.viewDialog"
        />
        <AppButton :disabled="!DialogStore.data.stagedDialog.id" label="Delete"
                   @click="deleteDialog"
        />
    </div>

    <div class="dialog-container">
        <div class="dialog-container-head">
            <input class="dialog-container-head-title" v-model="DialogStore.data.stagedDialog.title"
                   placeholder="Required: Dialog Title"
            />
        </div>
        <input v-model="DialogStore.data.stagedDialog.media"/>
        <textarea class="dialog-description" v-model="DialogStore.data.stagedDialog.description"/>

        <div class="dialog-body">
            <draggable :list="DialogStore.data.stagedDialog.sentences" itemKey="id"
                       @end="updatePosition()"
                       class="draggable">
                <template #item="{ element, index }">
                    <div class="db-item">
                        <DialogLine :sentence="element" @click="SentenceStore.toggleSelectSentence(index)"/>
                        <img src="/img/trash.svg" alt="Delete"
                             v-show="DialogStore.data.stagedDialog.sentences.length > 0"
                             @click="removeSentence(index)"/>
                    </div>
                </template>
            </draggable>
        </div>
    </div>

    <div class="db-build-buttons">
        <AppButton label="Add Sentence" @click="addSentence"/>
    </div>

    <AppNotification ref="notification"/>
</template>
