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
        scoreManager.score.scorableType = 'activity';

        exercises.value = data.activity.document.blocks
            .filter(b => b.type === 'exercises')
            .flatMap(b => b.items.map(item => ({
                ...item,
                response: item.type === 'match' ? [] : null,
            })));

        data.isLoading = false;
    };

    const submitActivity = () => {
        data.activity.document.blocks.forEach(block => {
            if (block.type === 'exercises') {
                block.items.forEach(item => {
                    const userExercise = getExerciseById(item.id);
                    item.response = userExercise.response;

                    if (block.exerciseType === 'match') {
                        item.pairs.forEach(pair => {
                            pair.correct = userExercise.response.some(r =>
                                r.start === pair.start && r.end === pair.end
                            );
                        });

                    } else if (block.exerciseType === 'input') {
                        item.correct = item.answers.some(a =>
                            a.trim().toLowerCase() === userExercise.response?.trim().toLowerCase()
                        );

                    } else if (block.exerciseType === 'select') {
                        item.correct = item.answerId === userExercise.response;
                    }
                });
            }
        });

        scoreManager.score.results = data.activity.document.blocks;
        scoreManager.calculateScore();

        data.step = 'results';

        nextTick(() => {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });
    };

    const saveScore = () => {
        scoreManager.saveScore(data.activity.id, {
            onSuccess: () => {
                router.get(route('lessons.show', data.activity.lesson.global_position));
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
