import {reactive, ref} from "vue";
import {router} from "@inertiajs/vue3";
import {route} from 'ziggy-js';

export function useScoreManager() {
    const score = reactive({
        settings: {},
        score: 0,
        results: [],
    });

    const isSaved = ref(false);

    const markCorrect = (id) => {
        const result = score.results.find(r => r.id === id);

        if (result) {
            result.correct = true;
            calculateScore();
            isSaved.value = false;
        }
    }

    const calculateScore = () => {
        if (!score.results || score.results.length === 0) {
            score.score = 0;
            return;
        }

        const correctCount = score.results.filter(q => q.correct).length;
        score.score = correctCount / score.results.length;
    };

    const saveScore = async (scorableType, scorableId, options = {}) => {
        router.post(route('scores.store'), {
            scorable_type: scorableType,
            scorable_id: scorableId,
            settings: score.settings,
            score: score.score,
            results: score.results,
        }, {
            onSuccess: () => {
                console.log('Score saved successfully!');
                if (options.onSuccess) options.onSuccess();
            },
            onError: (errors) => {
                console.error('Error saving Score:', errors);
                if (options.onError) options.onError(errors);
            }
        });
    };

    const resetScore = () => {
        score.settings = {};
        score.score = 0;
        score.results = [];
    }

    return {
        score,
        markCorrect,
        calculateScore,
        saveScore,
        resetScore
    }
}
