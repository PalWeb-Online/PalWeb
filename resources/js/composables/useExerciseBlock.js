import { computed } from 'vue';
import { useActivityStore } from '../Pages/Academy/Activities/Stores/ActivityStore.js';
import { shuffle } from 'lodash';

export function useExerciseBlock(props) {
    const ActivityStore = useActivityStore();

    // todo: the problem with this is that matching block items don't have a `correct` key
    const isViewingResults = computed(() => {
        return props.block.items.length > 0 && Object.hasOwn(props.block.items[0], 'correct');
    });

    const processedItems = computed(() => {
        let items = props.block.items.map(item => {
            if (!isViewingResults.value && props.block.exerciseType === 'select' && item.shuffleOptions) {
                return {...item, displayOptions: shuffle([...item.options])};
            }
            return {...item, displayOptions: item.options};
        });

        if (!isViewingResults.value && props.block.shuffle) {
            items = shuffle(items);
        }

        return items;
    });

    return {
        ActivityStore,
        isViewingResults,
        processedItems,
    };
}
