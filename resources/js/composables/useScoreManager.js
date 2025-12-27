import {reactive, ref} from "vue";
import {router} from "@inertiajs/vue3";
import {route} from 'ziggy-js';

export function useScoreManager() {
    const score = reactive({
        scorableType: '',
        settings: {},
        score: 0,
        results: [],
    });

    const isSaved = ref(false);

    const formatter = new Intl.NumberFormat('en-US', {
        style: 'percent',
        maximumFractionDigits: 0,
    });

    const getScoreStats = (scoreObject) => {
        if (!scoreObject || !scoreObject.results?.length) {
            return { correct: 0, total: 0, formatted: '0%' };
        }

        let exercises;
        if (scoreObject.scorable_type === 'activity' || scoreObject.scorableType === 'activity') {
            exercises = scoreObject.results
                .filter(b => b.type === 'exercises')
                .flatMap(b => b.items);
        } else {
            exercises = scoreObject.results;
        }

        const correct = exercises.reduce((acc, item) => {
            if (item.type === 'match') {
                return acc + (item.pairs?.filter(p => p.correct).length || 0);
            }
            return acc + (item.correct ? 1 : 0);
        }, 0);

        const total = exercises.reduce((acc, item) => {
            return acc + (item.type === 'match' ? (item.pairs?.length || 0) : 1);
        }, 0);

        return {
            correct,
            total,
            formatted: formatter.format(scoreObject.score)
        };
    };

    const markCorrect = (id) => {
        const exercises = getScoreStats(score);
        const result = exercises.find(r => r.id === id);

        if (result) {
            result.correct = true;
            calculateScore();
            isSaved.value = false;
        }
    }

    const calculateScore = () => {
        const exercises = getScoreStats(score);

        if (exercises.length === 0) {
            score.score = 0;
            return;
        }

        const correctCount = exercises.filter(q => q.correct).length;
        score.score = correctCount / exercises.length;
    };

    const saveScore = async (scorableId, options = {}) => {
        router.post(route('scores.store'), {
            scorable_type: score.scorableType,
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
        score.scorableType = null;
        score.settings = {};
        score.score = 0;
        score.results = [];
    }

    return {
        score,
        formatter,
        getScoreStats,
        markCorrect,
        calculateScore,
        saveScore,
        resetScore
    }
}
