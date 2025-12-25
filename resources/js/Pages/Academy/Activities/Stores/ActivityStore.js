import {defineStore} from 'pinia';
import {nextTick, reactive, ref} from 'vue';
import {useScoreManager} from "../../../../composables/useScoreManager.js";
import {router} from "@inertiajs/vue3";

export const useActivityStore = defineStore('ActivityStore', () => {
    const scoreManager = useScoreManager();

    const data = reactive({
        step: 'activity',
        activity: null,
        isLoading: true,
    });

    const exercises = ref([]);

    const getExerciseById = (id) => {
        return exercises.value.find(ex => ex.id === id);
    };

    const startActivity = () => {
        exercises.value = data.activity.document.blocks
            .filter(b => b.type === 'exercises')
            .flatMap(b => b.items.map(item => ({
                ...item,
                response: item.type === 'match' ? [] : null,
            })));

        data.isLoading = false;
    };

    const submitActivity = () => {
        const results = [];

        exercises.value.forEach(ex => {
            if (ex.type === 'match') {
                ex.pairs.forEach(validPair => {
                    const userFoundIt = ex.response.some(userPair =>
                        userPair.start === validPair.start && userPair.end === validPair.end
                    );

                    results.push({
                        id: `${ex.id}_pair_${validPair.start}`,
                        parentId: ex.id,
                        type: 'match_pair',
                        start: validPair.start,
                        end: validPair.end,
                        response: ex.response.find(r => r.start === validPair.start)?.end || null,
                        correct: userFoundIt
                    });
                });

            } else {
                let isCorrect = false;

                if (ex.type === 'input') {
                    isCorrect = ex.answers.some(a => a.trim().toLowerCase() === ex.response?.trim().toLowerCase());

                } else if (ex.type === 'select') {
                    isCorrect = ex.answerId === ex.response;
                }

                results.push({
                    ...ex,
                    correct: isCorrect
                });
            }
        });

        scoreManager.score.results = results;
        scoreManager.calculateScore();

        data.step = 'results';

        nextTick(() => {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });
    };

    const saveScore = () => {
        scoreManager.saveScore('activity', data.activity.id, {
            onSuccess: () => {
                router.get(route('lessons.show', data.activity.lesson.slug));
            }
        });
    };

    const reset = () => {
        data.step = 'activity';
        data.activity = null;
        data.isLoading = true;
        exercises.value = [];
        scoreManager.resetScore();
    };

    return {
        data,
        score: scoreManager.score,
        isSaved: scoreManager.isSaved,
        exercises,
        getExerciseById,
        startActivity,
        submitActivity,
        markCorrect: scoreManager.markCorrect,
        saveScore,
        reset
    };
});
