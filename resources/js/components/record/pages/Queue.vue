<script setup>
import {ref} from 'vue';
import {useQueueStore} from '../stores/QueueStore.js';
import {useRecordStore} from '../stores/RecordStore';
import SearchGenie from '../../search/SearchGenie.vue';
import AppDialog from '../../AppDialog.vue';
import AppTooltip from '../../AppTooltip.vue';
import Draggable from 'vuedraggable';
import AppNotification from '../../AppNotification.vue';

const QueueStore = useQueueStore();
const RecordStore = useRecordStore();

const tooltip = ref(null);
const notification = ref(null);

const fetchDeckItems = async (deck) => {
    await QueueStore.fetchDeckItems(deck.id);
    notification.value.showNotification(`Added Deck to the Queue!`);
}

const remove = (index) => {
    QueueStore.removeItem(QueueStore.data.items[index]);
};
</script>

<template>
    <SearchGenie :context="'record'" @emitDeck="fetchDeckItems($event)"/>

    <div class="rw-page-title">
        <h2>Queue</h2>

        <AppDialog size="large">
            <template #trigger>
                <img alt="Info" src="/img/idea.svg"/>
            </template>
            <template #content>
                <div>What is the Queue?</div>
                <p>Terms have many Pronunciations. The <b>Queue</b> is the list of Pronunciation items you will be
                    recording with the <b>Record Wizard</b>.</p>
                <p><b>You may only queue up a maximum of 100 items.</b> After uploading your recordings, you
                    may return to the Queue page to refresh the list with another 100 items.</p>
                <p><b>You may only record any given item once — one Audio per Pronunciation per Speaker.</b> If an item
                    that you expected to see in the Queue seems to be missing, you have probably already recorded it.
                </p>

                <div>How do I fill the Queue?</div>
                <p>Click <b>Fetch Items</b> to automatically queue up the next 100 items in your Dialect available for
                    you to record, or as many as are needed to fill the Queue to 100. If you choose to discard any item,
                    you can click <b>Fetch Items</b> again to fill the Queue back up to 100 with the next available
                    items. With <b>Fetch Items</b>, items are sorted by Audio count: items with no Audios are listed
                    first, followed by those with at least one, then two & so on.</p>
                <p>Alternatively, use the <b>Search Genie</b> to search for a Deck of your choosing. Select it to queue
                    up any of its items that you haven't recorded yet. While you can't search for individual items to
                    add manually to the Queue, you can create a Deck of items you'd like to record & select it here.
                    With <b>Queue Deck</b>, items are not sorted by Audio count; they are listed in the order
                    in which they appear in the Deck.</p>
            </template>
        </AppDialog>
    </div>
    <div class="tip">
        <div class="material-symbols-rounded">info</div>
        <div class="tip-content">
            <p>Automatically generate a list of items to record, or use the <b>Search Genie</b> to fill the Queue with
                any
                valid items in the Deck of your choosing. You may manually reorder the Queue or remove any items that
                you do not wish to record. Once you are satisfied with the Queue, proceed to the next step to
                record.</p>
        </div>
    </div>

    <div class="rw-page__queue">
        <div class="rw-queue-body"
             v-if="QueueStore.data.items.length > 0">
            <draggable v-if="QueueStore.data.items.length > 0"
                       :list="QueueStore.data.items" itemKey="id"
                       id="rw-item-queue">
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
            <div class="rw-queue-buttons">
                <button :class="{ disabled: QueueStore.data.items.length >= 100}"
                        @mousemove="tooltip.showTooltip(QueueStore.data.items.length < 100 ? 'Automatically fill your Queue with up to 100 items.' : '(You already have 100 items in the Queue.)' , $event);"
                        @mouseleave="tooltip.hideTooltip()"
                        @click="QueueStore.fetchAutoItems">Fetch Items
                </button>

                <button :class="{ disabled: QueueStore.data.items.length <= 0}"
                        @mousemove="tooltip.showTooltip(QueueStore.data.items.length > 0 ? 'Remove all items from your Queue.' : '(Your Queue is already empty.)', $event);"
                        @mouseleave="tooltip.hideTooltip()"
                        @click="QueueStore.flushQueue()">Flush Queue
                </button>
            </div>

            <div class="rw-item-counter">{{ QueueStore.data.items.length }} of 100</div>
        </div>
    </div>

    <AppTooltip ref="tooltip"/>
    <AppNotification ref="notification"/>
</template>
