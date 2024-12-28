<script setup>
import {onMounted, ref} from 'vue';
import {useQueueStore} from "../stores/QueueStore.js";
import {useRecordStore} from '../stores/RecordStore';
import Draggable from 'vuedraggable';
import AppDialog from "../../AppDialog.vue";
import SearchGenie from "../../search/SearchGenie.vue";

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
        QueueStore.data.queue.name = '';
    }
}

const fetchAutoItems = async () => {
    await QueueStore.fetchAutoItems();
};

const fetchDeckItems = async (id) => {
    await QueueStore.flushQueue();
    await QueueStore.fetchDeckItems(id);
    dialogSelectDeck.value?.closeDialog();
}

onMounted(async () => {
    await QueueStore.fetchSavedDecks();
});
</script>

<template>
    <SearchGenie/>

    <div class="rw-page-title">
        <h2>Queue</h2>

        <AppDialog size="large">
            <template #trigger>
                <img alt="Info" src="/img/idea.svg"/>
            </template>
            <template #content>
                <div>What is the Queue?</div>
                <p>The Queue is the list of Pronunciation items you will be recording.</p>
                <p><b>You may only queue up a maximum of 100 items.</b> (After uploading your recordings, you
                    may
                    return to
                    the Queue page to refresh the list with another 100 items.)</p>
                <p><b>You may only record any given item once — one Audio per Pronunciation per Speaker.</b> If
                    an
                    item that you expected to see in the Queue seems to be missing, you have probably already
                    recorded it.</p>

                <div>What does Auto Queue do?</div>
                <p>
                    Click <b>Fetch</b> to automatically queue up the next 100 Pronunciations in your Dialect
                    available
                    for you to record. If you choose to discard any item, you can click <b>Fetch</b> again to
                    fill
                    the
                    Queue back up to 100 with the next available Pronunciations.</p>
                <p>With <b>Auto Queue</b>, Pronunciations are sorted by Audio count: Pronunciations with no
                    Audios
                    are listed first, followed by those with at least one, then two & so on.
                </p>
                <div>What does Queue Deck do?</div>
                <p>Click <b>Select</b> to pull up your Pinned Decks & choose any one of them to queue up any of
                    its
                    terms that you haven't recorded yet. You can only queue up one Deck at a time; click
                    <b>Select</b> again to switch your queued-up Deck. While you can't search for individual
                    terms
                    to add manually to the Queue, you can create a Deck of terms you'd like to record, keep it
                    pinned & select it here.</p>
                <p>With <b>Queue Deck</b>, Pronunciations are not sorted by Audio count; they are listed in the
                    order in which they appear in the Deck.</p>
            </template>
        </AppDialog>
    </div>
    <div class="tip">
        <div class="material-symbols-rounded">info</div>
        <div class="tip-content">
            <p>Select how you'd like to generate the list of items to record. You may manually reorder the Queue or
                remove any items that you do not wish to record. Once you are satisfied with the Queue, proceed to the
                next step to record.</p>
        </div>
    </div>

    <div class="rw-page__queue">
        <div class="rw-queue-head">
            <button :class="QueueStore.data.queue.type === 'auto' ? 'active' : ''" @click="queueAuto">Auto Queue
            </button>
            <button :class="QueueStore.data.queue.type === 'deck' ? 'active' : ''" @click="queueDeck">Queue Deck
            </button>
            <div class="rw-queue-name" v-if="QueueStore.data.queue.type === 'deck' && QueueStore.data.queue.name">
                {{ QueueStore.data.queue.name }}
            </div>
        </div>

        <div class="rw-queue-body"
             v-if="QueueStore.data.items.length > 0 || (QueueStore.data.queue.type === 'deck' && QueueStore.data.queue.name)">
            <draggable v-if="QueueStore.data.items.length > 0"
                       :list="QueueStore.data.items" id="rw-item-queue">
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
            <div class="rw-item-counter">{{ QueueStore.data.items.length }} of 100</div>

            <button v-if="QueueStore.data.queue.type === 'auto'"
                    :disabled="QueueStore.data.items.length >= 100"
                    @click="fetchAutoItems">Fetch Items
            </button>

            <button v-if="QueueStore.data.queue.type === 'deck'"
                    @click="dialogSelectDeck?.openDialog()">Select Deck
            </button>
        </div>
    </div>

    <AppDialog ref="dialogSelectDeck" title="Pinned Decks" size="large">
        <template #content>
            <button v-for="deck in QueueStore.data.decks" @click="fetchDeckItems(deck.id)">
                {{ deck.name }}
            </button>
        </template>
    </AppDialog>
</template>
