import {defineStore} from 'pinia';
import {reactive, ref, watch} from 'vue';
import {useSpeakerStore} from "./SpeakerStore.js";
import {useRecordStore} from './RecordStore';

export const useQueueStore = defineStore('QueueStore', () => {
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
                selectWord(i);
                break;
            }
        }
    };

    const selectWord = (index) => {
        selectedArray[selected.value] = false;
        selected.value = index;
        selectedArray[index] = 'selected';

        const selectedPronunciation = data.items[index];
        if (RecordStore.data.status[selectedPronunciation.id] === 'stashed') {
            // TODO: If you start recording & don't stop the recorder, then click on an item with a previously stashed audio, you will still be recording & will thus overwrite the stashed audio.
            //  If the recording has already been stashed, the recorder should be stopped. The problem is that the recorder object is in the Studio page; I don't think it is accessible here.
            //  I wish I could hit the `cancelRecord()` method in the Studio page from here.
            RecordStore.playRecord();
        }
    };

    const moveBackward = () => {
        for (let i = selected.value - 1; i >= 0; i--) {
            if (isSelectable(data.items[i])) {
                selectWord(i);
                return true;
            }
        }
        return false;
    };

    const moveForward = () => {
        for (let i = selected.value + 1; i < data.items.length; i++) {
            if (isSelectable(data.items[i])) {
                selectWord(i);
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

    const fetchDeck = async (deckId) => {
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

    const fetchAuto = async () => {
        try {
            const response = await axios.post('/record/pronunciations', {
                speaker_id: SpeakerStore.data.speaker.id,
                dialect_id: SpeakerStore.data.speaker.dialect_id,
                listedPronunciations: data.items,
            });

            if (response.data) {
                data.items.push(...response.data.pronunciations);
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
        // TODO: this should also clear the stash. e.g. try recording, then moving back to Speaker, then back to Studio.

        if (data.items.length === 0 || confirm('Doing this will clear your Queue. Proceed?')) {
            data.items = [];
            data.queue = {
                type: '',
                name: ''
            };
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
        selectWord,
        moveBackward,
        moveForward,
        isSelectable,
        fetchSavedDecks,
        fetchDeck,
        fetchAuto,
        removeItem,
        flushQueue,
    };
});
