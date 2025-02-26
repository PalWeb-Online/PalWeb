<script setup>
import {onMounted, watch} from "vue";
import {cloneDeep} from "lodash";
import {useNotificationStore} from "../../../stores/NotificationStore.js";
import {useStateStore} from "../stores/StateStore.js";
import {useSentenceStore} from "../stores/SentenceStore.js";
import draggable from 'vuedraggable';
import SentenceItem from "../ui/SentenceItem.vue";
import TermItem from "../ui/TermItem.vue";
import AppButton from "../../AppButton.vue";
import {useNavGuard} from "../../../composables/NavGuard.js";
import AppAlert from "../../AppAlert.vue";
import {useSearchGenie} from "../../../composables/useSearchGenie.js";
import {useSearchStore} from "../../../stores/SearchStore.js";

useSearchGenie('insert');
const SearchStore = useSearchStore();

const NotificationStore = useNotificationStore();
const StateStore = useStateStore();
const SentenceStore = useSentenceStore();

const { showAlert, handleConfirm, handleCancel } = useNavGuard(StateStore);

const insertTerm = (term) => {
    SentenceStore.data.stagedSentence.terms.push({
        id: term.id,
        term: term.term,
        category: term.category,
        translit: term.translit,
        glosses: term.glosses.map((gloss) => ({
            id: gloss.id, gloss: gloss.gloss,
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
    SentenceStore.data.stagedSentence.terms.push({
        sentencePivot: {
            sent_term: '',
            sent_translit: '',
            position: '',
        }
    });
    updatePosition();
}

const removeTerm = (index) => {
    SentenceStore.data.stagedSentence.terms.splice(index, 1);
    updatePosition();
}

const updatePosition = () => {
    SentenceStore.data.stagedSentence.terms.forEach((term, index) => {
        term.sentencePivot.position = index + 1;
    });
}

const saveSentence = async () => {
    const success = await SentenceStore.saveSentence();

    if (success) {
        NotificationStore.addNotification('The Sentence has been saved!');

    } else {
        NotificationStore.addNotification(StateStore.data.errorMessage, 'error');
    }
};

onMounted(async () => {
    SentenceStore.data.originalSentence = cloneDeep(SentenceStore.data.stagedSentence);

    watch(
        () => SearchStore.selectedModel,
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
        <img v-if="StateStore.data.mode === 'dialog'" src="/img/reverse.svg"
             @click="StateStore.data.step = 'dialog'" alt="Back"/>
        <div class="app-nav-interact-buttons">
            <AppButton :disabled="!StateStore.hasNavigationGuard || !StateStore.isValidRequest" label="Save"
                       @click="saveSentence"
            />
            <AppButton :disabled="!StateStore.hasNavigationGuard" label="Reset"
                       @click="SentenceStore.resetSentence"
            />
            <AppButton label="Add Term" @click="addTerm"/>
        </div>
    </div>

    <div class="sentence-container">
        <SentenceItem :sentence="SentenceStore.data.stagedSentence" size="l"
                      :speaker="StateStore.data.mode === 'dialog'"
                      :dialog="StateStore.data.mode === 'sentence'"
        />
        <draggable :list="SentenceStore.data.stagedSentence.terms" itemKey="id"
                   @end="updatePosition()"
                   class="draggable">
            <template #item="{ element, index }">
                <div class="db-item">
                    <TermItem :term="element"/>
                    <img src="/img/trash.svg" class="trash" alt="Delete"
                         v-show="SentenceStore.data.stagedSentence.terms.length > 0"
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
