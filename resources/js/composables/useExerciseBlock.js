import { computed } from 'vue';
import { useActivitySession } from './activities/useActivitySession.js';
import { shuffle } from 'lodash';

export function useExerciseBlock(props) {
    const ActivitySession = useActivitySession();

    const processedItems = computed(() => {
        let items = props.block.items.map(ex => {
            if (props.block.exerciseType === 'select') {
                return ex.shuffleOptions && !ActivitySession.isViewingResults
                    ? {...ex, displayOptions: shuffle([...ex.options])}
                    : {...ex, displayOptions: ex.options};
            }

            return {...ex};
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
