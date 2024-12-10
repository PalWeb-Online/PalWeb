<script setup>
import {onMounted, ref} from 'vue';
import {useQueueStore} from "../store/QueueStore.js";
import {useRecordStore} from '../store/RecordStore';
import Draggable from 'vuedraggable';
import WizardDialog from "../ui/WizardDialog.vue";

const QueueStore = useQueueStore();
const RecordStore = useRecordStore();

const dialogSelectDeck = ref(null);

const remove = (index) => {
    QueueStore.removeItem(QueueStore.data.items[index]);
};

const queueAuto = async () => {
    if (QueueStore.data.queue.type !== 'auto') {
        const flushed = await QueueStore.flushQueue();
        if (!flushed) return;

        QueueStore.data.queue.type = 'auto';
    }
};

const queueDeck = async () => {
    if (QueueStore.data.queue.type !== 'deck') {
        const flushed = await QueueStore.flushQueue();
        if (!flushed) return;

        QueueStore.data.queue.type = 'deck';
        QueueStore.data.queue.name = null;
    }
}

// TODO: Is this needed?
const fetchAuto = async () => {
    await QueueStore.fetchAuto();
};

const fetchDeck = async (id) => {
    await QueueStore.flushQueue();
    await QueueStore.fetchDeck(id);
    dialogSelectDeck.value?.closeDialog();
}

onMounted(async () => {
    await QueueStore.fetchSavedDecks();
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
            <button :class="QueueStore.data.queue.type === 'auto' ? 'active' : ''" @click="queueAuto">Auto Queue
            </button>
            <button :class="QueueStore.data.queue.type === 'deck' ? 'active' : ''" @click="queueDeck">Queue Deck
            </button>
            <div class="rw-queue-name" v-if="QueueStore.data.queue.type === 'deck' && QueueStore.data.queue.name">{{ QueueStore.data.queue.name }}</div>
        </div>

        <div class="rw-queue-body"
             v-if="QueueStore.data.items.length > 0 || (QueueStore.data.queue.type === 'deck' && QueueStore.data.queue.name)">
            <draggable v-if="QueueStore.data.items.length > 0"
                :list="QueueStore.data.items" id="wizard-queue">
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
            <div class="wizard-queue-count">{{ QueueStore.data.items.length }} of 100</div>

            <button v-if="QueueStore.data.queue.type === 'auto'"
                    :disabled="QueueStore.data.items.length >= 100"
                    @click="fetchAuto">Fetch Items
            </button>

            <button v-if="QueueStore.data.queue.type === 'deck'"
                    @click="dialogSelectDeck?.openDialog()">Select Deck
            </button>
        </div>
    </div>

    <WizardDialog ref="dialogSelectDeck" title="Pinned Decks" size="large">
        <template #content>
            <button v-for="deck in QueueStore.data.decks" @click="fetchDeck(deck.id)">
                {{ deck.name }}
            </button>
        </template>
    </WizardDialog>
</template>
