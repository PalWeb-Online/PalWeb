<script setup>
import {onMounted, ref} from 'vue';
import {useRecordStore} from '../store/RecordStore';
import Draggable from 'vuedraggable';
import WizardDialog from "../ui/WizardDialog.vue";

const RecordStore = useRecordStore();

const dialogSelectDeck = ref(null);

const remove = (index) => {
    RecordStore.removePronunciation(RecordStore.data.pronunciations[index]);
};

const queueAuto = async () => {
    if (RecordStore.data.queue.type !== 'auto') {
        const flushed = await RecordStore.flushQueue();
        if (!flushed) return;

        RecordStore.data.queue.type = 'auto';
    }
};

const queueDeck = async () => {
    if (RecordStore.data.queue.type !== 'deck') {
        const flushed = await RecordStore.flushQueue();
        if (!flushed) return;

        RecordStore.data.queue.type = 'deck';
        RecordStore.data.queue.name = null;
    }
}

// TODO: Is this needed?
const fetchAuto = async () => {
    await RecordStore.fetchAuto();
};

const fetchDeck = async (id) => {
    await RecordStore.flushQueue();
    await RecordStore.fetchDeck(id);
    dialogSelectDeck.value?.closeDialog();
}

onMounted(async () => {
    await RecordStore.fetchSavedDecks();
});
</script>

<template>
    <div class="wizard-page-title">
        <h2>Queue</h2>
    </div>
    <div class="tip">
        <div class="material-symbols-rounded">info</div>
        <div class="tip-content">
            <p>Select how you'd like to generate the list of items to record. You may manually reorder the Queue or
                remove any items that you do not wish to record. Once you are satisfied with the Queue, proceed to the
                next step to record.</p>
        </div>
    </div>

    <div class="wizard-section-container">
        <div class="rw-queue-head">
            <button :class="RecordStore.data.queue.type === 'auto' ? 'active' : ''" @click="queueAuto">Auto Queue
            </button>
            <button :class="RecordStore.data.queue.type === 'deck' ? 'active' : ''" @click="queueDeck">Queue Deck
            </button>
            <div class="rw-queue-name" v-if="RecordStore.data.queue.type === 'deck' && RecordStore.data.queue.name">{{ RecordStore.data.queue.name }}</div>
        </div>

        <div class="rw-queue-body"
             v-if="RecordStore.data.pronunciations.length > 0 || (RecordStore.data.queue.type === 'deck' && RecordStore.data.queue.name)">
            <draggable v-if="RecordStore.data.pronunciations.length > 0"
                :list="RecordStore.data.pronunciations" id="wizard-queue">
                <template #item="{ element, index }">
                    <li>
                        <div>
                            <div>
                                {{ index + 1 }}. <span>{{ element.term }}</span> ({{ element.translit }})
                            </div>
                        </div>
                        <img class="trash" src="/img/trash.svg" alt="Delete" @click="remove(index)"/>
                    </li>
                </template>
            </draggable>
            <div v-else>
                Sorry, there are no valid items in your Dialect available for you to record.
            </div>
        </div>

        <div class="rw-queue-foot">
            <div class="wizard-queue-count">{{ RecordStore.data.pronunciations.length }} of 100</div>

            <button v-if="RecordStore.data.queue.type === 'auto'"
                    :disabled="RecordStore.data.pronunciations.length >= 100"
                    @click="fetchAuto">Fetch Items
            </button>

            <button v-if="RecordStore.data.queue.type === 'deck'"
                    @click="dialogSelectDeck?.openDialog()">Select Deck
            </button>
        </div>
    </div>

    <WizardDialog ref="dialogSelectDeck" title="Pinned Decks" size="large">
        <template #content>
            <button v-for="deck in RecordStore.data.decks" @click="fetchDeck(deck.id)">
                {{ deck.name }}
            </button>
        </template>
    </WizardDialog>
</template>
