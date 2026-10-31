import {computed} from 'vue';
import {useActivitySession} from './activities/useActivitySession.js';
import {shuffle} from 'lodash';

export function useExerciseBlock(props) {
    const session = useActivitySession();

    const isViewingResults = computed(() => {
        return !session || session.isViewingResults;
    });

    const getExerciseById = (id) => {
        return session?.getExerciseById(id) ?? props.block.items.find(item => item.id === id);
    };

    const getExerciseResponse = (exercise) => {
        return isViewingResults.value
            ? exercise.response
            : getExerciseById(exercise.id)?.response;
    };

    const setExerciseResponse = (id, response) => {
        if (isViewingResults.value) return;

        const exercise = getExerciseById(id);
        if (exercise) exercise.response = response;
    };

    const processedItems = computed(() => {
        let items = props.block.items.map(ex => {
            if (props.block.exerciseType === 'select') {
                return ex.shuffleOptions && !isViewingResults.value
                    ? {
                        ...ex,
                        displayOptions: shuffle([...ex.options]),
                    }
                    : {
                        ...ex,
                        displayOptions: ex.options,
                    };
            }

            return { ...ex };
        });

        if (!isViewingResults.value && props.block.shuffle) {
            items = shuffle(items);
        }

        return items;
    });

    return {
        activity: session?.activity ?? null,
        isViewingResults,
        markCorrect: session?.markCorrect,
        getExerciseById,
        getExerciseResponse,
        setExerciseResponse,
        processedItems,
    };
}
