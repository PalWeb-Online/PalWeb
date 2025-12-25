import { computed } from 'vue';
import { useActivityStore } from '../Pages/Academy/Activities/Stores/ActivityStore.js';
import { shuffle } from 'lodash';

export function useExerciseBase(props) {
    const ActivityStore = useActivityStore();

    const getItemState = (itemId) => {
        if (props.results) {
            return props.results.find(r => r.id === itemId);
        }

        return ActivityStore.getExerciseById(itemId);
    };

    const processedItems = computed(() => {
        let items = props.block.items.map(item => {
            if (!props.results && props.block.exerciseType === 'select' && item.shuffleOptions) {
                return {...item, displayOptions: shuffle([...item.options])};
            }
            return {...item, displayOptions: item.options};
        });

        if (!props.results && props.block.shuffle) {
            items = shuffle(items);
        }

        return items;
    });

    return {
        ActivityStore,
        getItemState,
        processedItems,
    };
}
