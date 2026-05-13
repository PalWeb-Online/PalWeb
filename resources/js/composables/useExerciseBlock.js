import { computed } from 'vue';
import { useActivitySession } from './activities/useActivitySession.js';
import { shuffle } from 'lodash';

export function useExerciseBlock(props) {
    const ActivitySession = useActivitySession();

    const processedItems = computed(() => {
        let items = props.block.items.map(item => {
            if (!ActivitySession.isViewingResults && props.block.exerciseType === 'select' && item.shuffleOptions) {
                return {...item, displayOptions: shuffle([...item.options])};
            }
            return {...item, displayOptions: item.options};
        });

        if (!ActivitySession.isViewingResults && props.block.shuffle) {
            items = shuffle(items);
        }

        return items;
    });

    return {
        ActivitySession,
        processedItems,
    };
}
