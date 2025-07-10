<script setup>
import {onMounted, ref, watch} from 'vue';
import {useQueueStore} from '../stores/QueueStore.js';
import Draggable from 'vuedraggable';
import {useSearchStore} from "../../../../stores/SearchStore.js";
import AppTooltip from "../../../../components/AppTooltip.vue";
import PopupWindow from "../../../../components/Modals/PopupWindow.vue";
import AppTip from "../../../../components/AppTip.vue";

const SearchStore = useSearchStore();
const QueueStore = useQueueStore();

const tooltip = ref(null);

const remove = (index) => {
    QueueStore.removeItem(QueueStore.queue[index]);
};

onMounted(() => {
    watch(
        () => SearchStore.data.selectedModel,
        (newModel) => {
            if (newModel) {
                QueueStore.fetchDeckItems(newModel.id)
                SearchStore.deselectModel();
            }
        }
    );
});
</script>

<template>
    <div class="window-section-head">
        <h2>Queue</h2>

        <PopupWindow title="(RW) Queue">
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
        </PopupWindow>
    </div>
    <AppTip>
        <p>Automatically generate a list of items to record, or use the <b>Search Genie</b> to fill the Queue with
            any
            valid items in the Deck of your choosing. You may manually reorder the Queue or remove any items that
            you do not wish to record. Once you are satisfied with the Queue, proceed to the next step to
            record.</p>
    </AppTip>

    <div class="rw-page__queue">
        <div class="rw-queue-body"
             v-if="QueueStore.queue.length > 0">
            <draggable v-if="QueueStore.queue.length > 0"
                       :list="QueueStore.queue" itemKey="id"
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
                <button :disabled="QueueStore.queue.length >= 100"
                        @mousemove="tooltip.showTooltip(QueueStore.queue.length < 100 ? 'Queue up all the Terms in a Deck for recording.' : '(You already have 100 items in the Queue.)', $event);"
                        @mouseleave="tooltip.hideTooltip()"
                        @click="SearchStore.openSearchGenie('insert', 'decks')">Load Deck
                </button>

                <button :disabled="QueueStore.queue.length >= 100"
                        @mousemove="tooltip.showTooltip(QueueStore.queue.length < 100 ? 'Automatically fill your Queue with up to 100 items.' : '(You already have 100 items in the Queue.)', $event);"
                        @mouseleave="tooltip.hideTooltip()"
                        @click="QueueStore.fetchAutoItems">Fetch Items
                </button>

                <button :disabled="QueueStore.queue.length <= 0"
                        @mousemove="tooltip.showTooltip(QueueStore.queue.length > 0 ? 'Remove all items from your Queue.' : '(Your Queue is already empty.)', $event);"
                        @mouseleave="tooltip.hideTooltip()"
                        @click="QueueStore.flushQueue()">Flush Queue
                </button>
            </div>

            <div class="rw-item-counter">{{ QueueStore.queue.length }} of 100</div>
        </div>
    </div>

    <AppTooltip ref="tooltip"/>
</template>
