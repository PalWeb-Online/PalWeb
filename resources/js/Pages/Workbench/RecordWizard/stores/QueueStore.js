import {defineStore} from 'pinia';
import {reactive, ref, watch} from 'vue';
import {useRecordWizardStore} from "./RecordWizardStore.js";
import {useRecordStore} from './RecordStore';
import {route} from "ziggy-js";

export const useQueueStore = defineStore('QueueStore', () => {
    const RecordWizardStore = useRecordWizardStore();
    const RecordStore = useRecordStore();

    const queue = reactive([]);

    const selected = ref(0);
    const selectedArray = reactive([]);

    watch(selected, (newIndex) => {
        const container = document.querySelector('section:first-child');
        const selectedItem = container?.querySelectorAll('.rw-record-queue > div')[newIndex];

        if (selectedItem && container) {
            const itemOffsetTop = selectedItem.offsetTop;
            const itemHeight = selectedItem.offsetHeight;
            const containerHeight = container.offsetHeight;

            const scrollPosition = itemOffsetTop - (containerHeight / 2) + (itemHeight / 2);
            container.scrollTo({
                top: scrollPosition,
                behavior: 'smooth',
            });
        }
    });

    const initSelection = () => {
        selectedArray.splice(0, selectedArray.length);
        for (let i = 0; i < queue.length; i++) {
            selectedArray.push(false);
        }

        for (let i = 0; i < queue.length; i++) {
            if (isSelectable(queue[i])) {
                selectItem(i);
                break;
            }
        }
    };

    const selectItem = (index) => {
        selectedArray[selected.value] = false;
        selected.value = index;
        selectedArray[index] = 'selected';

        const newSelected = queue[index];
        if (RecordStore.data.status[newSelected.id] === 'stashed') {
            if (RecordWizardStore.data.isRecording) RecordStore.stopRecording();
            RecordStore.playRecord();
        }
    };

    const moveBackward = () => {
        for (let i = selected.value - 1; i >= 0; i--) {
            if (isSelectable(queue[i])) {
                selectItem(i);
                return true;
            }
        }
        return false;
    };

    const moveForward = () => {
        for (let i = selected.value + 1; i < queue.length; i++) {
            if (isSelectable(queue[i])) {
                selectItem(i);
                return true;
            }
        }
        return false;
    };

    const isSelectable = (element) => {
        return !['up', 'ready', 'stashing'].includes(RecordStore.data.status[element]);
    }

    const fetchAutoItems = async () => {
        try {
            const response = await axios.post(route('record-wizard.get.auto'), {
                speaker_id: RecordWizardStore.speaker.id,
                dialect_id: RecordWizardStore.speaker.dialect.id,
                queuedItems: queue,
            });

            if (response.data) {
                queue.push(...response.data.items);
            }
        } catch (error) {
            console.error('Error loading items:', error);
        }
    };

    const fetchDeckItems = async (id) => {
        try {
            const response = await axios.post(route('record-wizard.get.deck', id), {
                speaker_id: RecordWizardStore.speaker.id,
                dialect_id: RecordWizardStore.speaker.dialect.id,
                queuedItems: queue,
            });

            if (response.data) {
                queue.push(...response.data.items);
            }
        } catch (error) {
            console.error(`Error fetching deck with ID ${id}:`, error);
        }
    };

    const removeItem = async (pronunciation) => {
        const index = queue.indexOf(pronunciation);

        if (RecordStore.data.records[pronunciation.id]) {
            if (!confirm('You have a stashed recording for this item. Are you sure you would like to remove the item from your Queue before uploading? Your stashed recording will be lost.')) return false;

            const discarded = await RecordStore.discardRecord(pronunciation.id);
            if (!discarded) return false;
        }

        queue.splice(index, 1);
        return true;
    };

    const flushQueue = async () => {
        if (queue.length === 0 || confirm('Doing this will clear your Queue & delete all your currently stashed recordings, if any. Proceed?')) {
            queue.splice(0, queue.length);

            await RecordStore.clearStash();
            return true;
        }
        return false;
    };

    return {
        queue,
        selected,
        selectedArray,
        initSelection,
        selectItem,
        moveBackward,
        moveForward,
        isSelectable,
        fetchDeckItems,
        fetchAutoItems,
        removeItem,
        flushQueue,
    };
});
