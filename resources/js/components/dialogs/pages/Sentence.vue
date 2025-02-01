<script setup>
import {onMounted, ref} from "vue";
import {cloneDeep} from "lodash";
import {useStateStore} from "../stores/StateStore.js";
import {useDialogStore} from "../stores/DialogStore.js";
import {useSentenceStore} from "../stores/SentenceStore.js";
import draggable from 'vuedraggable';
import SearchGenie from "../../search/SearchGenie.vue";
import SentenceItem from "../ui/SentenceItem.vue";
import TermItem from "../ui/TermItem.vue";
import AppButton from "../../AppButton.vue";
import AppNotification from "../../AppNotification.vue";

const StateStore = useStateStore();
const DialogStore = useDialogStore();
const SentenceStore = useSentenceStore();
const notification = ref(null);

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
    notification.value.showNotification(`Added ${term.term} to the Sentence!`);
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
        notification.value.showNotification('The Sentence has been saved!');

    } else {
        notification.value.showNotification(StateStore.data.errorMessage, 'error');
    }
};

const deleteSentence = async () => {
    const success = await SentenceStore.deleteSentence();

    if (success) {
        // or make the whole thing null?
        SentenceStore.data.stagedSentence.id = null;
        notification.value.showNotification('The Sentence has been deleted!');

        setTimeout(() => {
            StateStore.data.step = 'dialog';
        }, 1000);

    } else {
        notification.value.showNotification(StateStore.data.errorMessage, 'error');
    }
};

onMounted(async () => {
    SentenceStore.data.originalSentence = cloneDeep(SentenceStore.data.stagedSentence);
});
</script>

<template>
    <SearchGenie :context="'builder'" @emitTerm="insertTerm($event)"/>

    <div class="app-nav-interact">
        <AppButton :disabled="!StateStore.hasUnsavedChanges || !StateStore.isValidRequest" label="Save"
                   @click="saveSentence"
        />
        <AppButton :disabled="!StateStore.hasUnsavedChanges" label="Reset"
                   @click="SentenceStore.resetSentence"
        />
        <AppButton :disabled="!SentenceStore.data.stagedSentence.id" label="View"
                   @click="SentenceStore.viewSentence"
        />
        <AppButton :disabled="!SentenceStore.data.stagedSentence.id" label="Delete"
                   @click="deleteSentence"
        />
    </div>

    <SentenceItem :sentence="SentenceStore.data.stagedSentence" size="l"
                  :speaker="DialogStore.data.stagedDialog.id"
                  :dialog="!DialogStore.data.stagedDialog.id"
    />

    <draggable :list="SentenceStore.data.stagedSentence.terms" itemKey="id"
               @end="updatePosition()"
               class="draggable">
        <template #item="{ element, index }">
            <div class="db-item">
                <TermItem :term="element"/>
                <img src="/img/trash.svg" alt="Delete"
                     v-show="SentenceStore.data.stagedSentence.terms.length > 0"
                     @click="removeTerm(index)"/>
            </div>
        </template>
    </draggable>

    <div class="app-nav-interact">
        <AppButton label="Add Term" @click="addTerm"/>
    </div>

    <AppNotification ref="notification"/>
</template>
