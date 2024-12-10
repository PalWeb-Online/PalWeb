import {defineStore} from 'pinia';
import {reactive, ref, watch} from 'vue';
import {useStateStore} from "./StateStore.js";
import {useSpeakerStore} from "./SpeakerStore.js";
import {useRecordStore} from './RecordStore';

export const useQueueStore = defineStore('QueueStore', () => {
    const StateStore = useStateStore();
    const SpeakerStore = useSpeakerStore();
    const RecordStore = useRecordStore();

    const data = reactive({
        queue: {
            type: '',
            name: ''
        },
        decks: [],
        items: [],
    });

    const selected = ref(0);
    const autoScroll = ref(true);
    const selectedArray = reactive([]);

    watch(selected, () => {
        if (autoScroll.value) {
            // Logic for auto-scrolling
        }
    });

    const initSelection = () => {
        selectedArray.splice(0, selectedArray.length);
        for (let i = 0; i < data.items.length; i++) {
            selectedArray.push(false);
        }

        for (let i = 0; i < data.items.length; i++) {
            if (isSelectable(data.items[i])) {
                selectItem(i);
                break;
            }
        }
    };

    const selectItem = (index) => {
        selectedArray[selected.value] = false;
        selected.value = index;
        selectedArray[index] = 'selected';

        const newSelected = data.items[index];
        if (RecordStore.data.status[newSelected.id] === 'stashed') {
            if (StateStore.data.isRecording) RecordStore.stopRecording();
            RecordStore.playRecord();
        }
    };

    const moveBackward = () => {
        for (let i = selected.value - 1; i >= 0; i--) {
            if (isSelectable(data.items[i])) {
                selectItem(i);
                return true;
            }
        }
        return false;
    };

    const moveForward = () => {
        for (let i = selected.value + 1; i < data.items.length; i++) {
            if (isSelectable(data.items[i])) {
                selectItem(i);
                return true;
            }
        }
        return false;
    };

    const isSelectable = (element) => {
        return !['up', 'ready', 'stashing'].includes(RecordStore.data.status[element]);
    }

    // const beforeSelectionChange = () => true;
    // const afterSelectionChange = () => true;

    const fetchSavedDecks = async () => {
        try {
            const response = await axios.get('/record/decks');
            if (response.data && response.data.decks) {
                data.decks = response.data.decks;
            }
        } catch (error) {
            console.error('Error fetching saved decks:', error);
        }
    };

    const fetchDeckItems = async (deckId) => {
        try {
            const response = await axios.get(`/record/decks/${deckId}`);
            if (response.data) {
                data.items.push(...response.data.items);
                data.queue = {type: 'deck', name: response.data.deck.name};
            }
        } catch (error) {
            console.error(`Error fetching deck with ID ${deckId}:`, error);
        }
    };

    const fetchAutoItems = async () => {
        try {
            const response = await axios.post('/record/pronunciations', {
                speaker_id: SpeakerStore.data.speaker.id,
                dialect_id: SpeakerStore.data.speaker.dialect_id,
                queuedItems: data.items,
            });

            if (response.data) {
                data.items.push(...response.data.items);
                data.queue.name = 'Auto Queue';
            }
        } catch (error) {
            console.error('Error loading items:', error);
        }
    };

    const removeItem = async (pronunciation) => {
        const index = data.items.indexOf(pronunciation);

        if (RecordStore.data.records[pronunciation.id]) {
            const confirmed = confirm('You have a stashed recording for this item. Are you sure you would like to remove the item from your Queue before uploading? Your stashed recording will be deleted.');
            if (!confirmed) return false;

            const discarded = await RecordStore.discardRecord(pronunciation.id);
            if (!discarded) return false;
        }

        data.items.splice(index, 1);
        return true;
    };

    const flushQueue = async () => {
        if (data.items.length === 0 || confirm('Doing this will clear your Queue & delete all your currently stashed recordings, if any. Proceed?')) {
            data.items = [];
            data.queue = {
                type: '',
                name: ''
            };

            RecordStore.clearStash();
            return true;
        }
        return false;
    };

    return {
        data,
        selected,
        selectedArray,
        autoScroll,
        initSelection,
        selectItem,
        moveBackward,
        moveForward,
        isSelectable,
        fetchSavedDecks,
        fetchDeckItems,
        fetchAutoItems,
        removeItem,
        flushQueue,
    };
});
