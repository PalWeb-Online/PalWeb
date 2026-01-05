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
        <h2>{{ $t('sound-booth.queue.queue') }}</h2>

        <PopupWindow title="Sound Booth (Queue)">
            <div>What is the Queue?</div>
            <p>Terms have many Pronunciations. The <b>Queue</b> is the list of Pronunciation items you will be
                recording with the <b>Sound Booth</b>.</p>
            <p><b>You may only queue up a maximum of 100 items.</b> After uploading your recordings, you
                may return to the Queue page to refresh the list with another 100 items.</p>
            <p><b>You may only record any given item once — one Audio per Pronunciation per Speaker.</b> If an item
                that you expected to see in the Queue seems to be missing, you have probably already recorded it.
            </p>

            <div>How do I fill the Queue?</div>
            <p>Select <b>Fetch Items</b> to automatically queue up the next 100 items in your Dialect available for
                you to record, or as many as are needed to fill the Queue to 100. If you choose to discard any item,
                you can click <b>Fetch Items</b> again to fill the Queue back up to 100 with the next available
                items. With <b>Fetch Items</b>, items are sorted by Audio count: items with no Audios are listed
                first, followed by those with at least one, then two & so on.</p>
            <p>Alternatively, select <b>Load Deck</b> to pull up the <b>Search Genie</b> & search for a Deck of your
                choosing. Select it to queue up any of its items that you haven't recorded yet. While you can't search
                for individual items to add manually to the Queue, you can create a Deck of items you'd like to record &
                select it here. With this option, items are not sorted by Audio count; they are listed in the
                order in which they appear in the Deck.</p>
        </PopupWindow>
    </div>
    <AppTip>
        <p>Use <b>Fetch Items</b> to automatically generate a list of items to record, or <b>Load Deck</b> to fill the
            Queue with any valid items in a Deck of your choosing. You may manually reorder the Queue or remove any
            items that you do not wish to record. Once you are satisfied with the Queue, proceed to the next step to
            record.</p>
    </AppTip>

    <div class="rw-page__queue">
        <div class="rw-queue-body"
             v-if="QueueStore.queue.length > 0">
            <draggable v-if="QueueStore.queue.length > 0"
                       :list="QueueStore.queue" itemKey="id" handle=".handle"
                       id="rw-item-queue">
                <template #item="{ element, index }">
                    <li class="draggable-item">
                        <span class="delete material-symbols-rounded" style="cursor: pointer"
                              @click="remove(index)">delete</span>
                        <span>{{ index + 1 }}.</span>
                        <div><span>{{ element.term.term }}</span> ({{ element.translit }})</div>
                        <span class="handle material-symbols-rounded">drag_indicator</span>
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
                        @mousemove="tooltip.showTooltip(QueueStore.queue.length < 100 ? $t('sound-booth.queue.load.message') : $t('sound-booth.queue.status.full'), $event);"
                        @mouseleave="tooltip.hideTooltip()"
                        @click="SearchStore.openSearchGenie('insert', 'decks')">
                    {{ $t('sound-booth.queue.load.button') }}
                </button>

                <button :disabled="QueueStore.queue.length >= 100"
                        @mousemove="tooltip.showTooltip(QueueStore.queue.length < 100 ? $t('sound-booth.queue.auto.message') : $t('sound-booth.queue.status.full'), $event);"
                        @mouseleave="tooltip.hideTooltip()"
                        @click="QueueStore.fetchAutoItems">
                    {{ $t('sound-booth.queue.auto.button') }}
                </button>

                <button :disabled="QueueStore.queue.length <= 0"
                        @mousemove="tooltip.showTooltip(QueueStore.queue.length > 0 ? $t('sound-booth.queue.clear.message') : $t('sound-booth.queue.status.empty'), $event);"
                        @mouseleave="tooltip.hideTooltip()"
                        @click="QueueStore.flushQueue()">
                    {{ $t('sound-booth.queue.clear.button') }}
                </button>
            </div>

            <div class="rw-item-counter">{{ QueueStore.queue.length }} / 100</div>
        </div>
    </div>

    <AppTooltip ref="tooltip"/>
</template>
