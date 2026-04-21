import {computed} from 'vue';
import {useActivityStore} from '../Pages/Academy/Activities/Stores/ActivityStore.js';
import {shuffle} from 'lodash';

export function useExerciseBlock(props) {
    const ActivityStore = useActivityStore();

    // todo: the problem with this is that matching block items don't have a `correct` key
    const isViewingResults = computed(() => {
        return props.block.items.length > 0 && Object.hasOwn(props.block.items[0], 'correct');
    });

    const processedItems = computed(() => {
        let items = props.block.items.map(ex => {
            if (props.block.exerciseType === 'select') {
                return ex.shuffleOptions && !isViewingResults.value
                    ? {...ex, displayOptions: shuffle([...ex.options])}
                    : {...ex, displayOptions: ex.options};
            }

            return {...ex};
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
