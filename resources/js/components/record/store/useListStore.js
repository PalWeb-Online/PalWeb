import {reactive, ref, watch} from 'vue';
import useRecordStore from './useRecordStore';

let store;

export default function useListStore() {
    if (!store) {

        const recordStore = useRecordStore();
        const pronunciations = ref(recordStore.data.pronunciations);
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
            selectedArray[index] = 'mwe-rw-selected';
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

        const isSelectable = () => true; // Update with custom logic
        const beforeSelectionChange = () => true; // Update with custom logic
        const afterSelectionChange = () => true; // Update with custom logic

        store = {
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
    }

    return store;
}
