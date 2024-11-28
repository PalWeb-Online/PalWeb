import {defineStore} from 'pinia';
import {reactive, ref, watch} from 'vue';
import {useRecordStore} from './RecordStore';

export const useListStore = defineStore('ListStore', () => {
    const RecordStore = useRecordStore();

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
        for (let i = 0; i < RecordStore.data.pronunciations.length; i++) {
            selectedArray.push(false);
        }

        for (let i = 0; i < RecordStore.data.pronunciations.length; i++) {
            if (isSelectable(RecordStore.data.pronunciations[i])) {
                selectWord(i);
                break;
            }
        }
    };

    const selectWord = (index) => {
        const data = beforeSelectionChange();
        selectedArray[selected.value] = false;
        selected.value = index;
        selectedArray[index] = 'selected';
        afterSelectionChange(data);

        const selectedPronunciation = RecordStore.data.pronunciations[index];
        if (RecordStore.data.status[selectedPronunciation.id] === 'stashed') {
            // TODO: Stop recording previously if the recording has been stashed.
            // TODO: if you start recording & don't stop it, then click on another word, you will re-record it
            // cancelRecord();
            RecordStore.playRecord();
        }
    };

    const moveBackward = () => {
        for (let i = selected.value - 1; i >= 0; i--) {
            if (isSelectable(RecordStore.data.pronunciations[i])) {
                selectWord(i);
                return true;
            }
        }
        return false;
    };

    const moveForward = () => {
        for (let i = selected.value + 1; i < RecordStore.data.pronunciations.length; i++) {
            if (isSelectable(RecordStore.data.pronunciations[i])) {
                selectWord(i);
                return true;
            }
        }
        return false;
    };

    const isSelectable = (element) => {
        return !['up', 'ready', 'stashing'].includes(RecordStore.data.status[element]);
    }

    const beforeSelectionChange = () => true;
    const afterSelectionChange = () => true;

    return {
        selected,
        selectedArray,
        autoScroll,
        initSelection,
        selectWord,
        moveBackward,
        moveForward,
        isSelectable,
        beforeSelectionChange,
        afterSelectionChange,
    };
});
