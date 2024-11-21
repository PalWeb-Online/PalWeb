import { defineStore } from 'pinia';
import { reactive, ref, watch } from 'vue';
import { useRecordStore } from './RecordStore';

export const useListStore = defineStore('ListStore', () => {
    const RecordStore = useRecordStore();

    const pronunciations = ref(RecordStore.data.pronunciations);
    const selected = ref(0);
    const autoScroll = ref(true);
    const selectedArray = reactive([]);

    watch(selected, () => {
        if (autoScroll.value) {
            // Logic for auto-scrolling
        }
    });

    const initSelection = () => {
        selectedArray.splice(0, selectedArray.length); // Clear selection
        for (let i = 0; i < pronunciations.value.length; i++) {
            selectedArray.push(false); // Initialize false for all pronunciations
        }

        // Select the first selectable word
        for (let i = 0; i < pronunciations.value.length; i++) {
            if (isSelectable(pronunciations.value[i])) {
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
    };

    const moveBackward = () => {
        for (let i = selected.value - 1; i >= 0; i--) {
            if (isSelectable(pronunciations.value[i])) {
                selectWord(i);
                return true;
            }
        }
        return false;
    };

    const moveForward = () => {
        for (let i = selected.value + 1; i < pronunciations.value.length; i++) {
            if (isSelectable(pronunciations.value[i])) {
                selectWord(i);
                return true;
            }
        }
        return false;
    };

    // Replace these with actual logic as needed
    const isSelectable = () => true;
    const beforeSelectionChange = () => true;
    const afterSelectionChange = () => true;

    return {
        pronunciations,
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
