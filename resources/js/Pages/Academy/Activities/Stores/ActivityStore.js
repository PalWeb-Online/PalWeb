import {defineStore} from 'pinia';
import {nextTick, reactive, ref} from 'vue';
import {useScoreManager} from "../../../../composables/useScoreManager.js";
import {router} from "@inertiajs/vue3";
import {shuffle} from 'lodash';

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
        scoreManager.score.scorable_type = 'activity';

        exercises.value = data.activity.document.blocks
            .filter(b => b.type === 'exercises')
            .flatMap(b => b.items.map(item => {
                const runtimeExercise = {
                    ...item,
                    response: ['match', 'sort'].includes(item.type) ? [] : null,
                };

                if (item.type === 'sort') {
                    runtimeExercise.shuffledItems = shuffle([...item.items]);
                    runtimeExercise.response = runtimeExercise.shuffledItems;
                }

                return runtimeExercise;
            }));

        data.isLoading = false;
    };

    const submitActivity = () => {
        data.activity.document.blocks.forEach(block => {
            if (block.type === 'exercises') {
                block.items.forEach(ex => {
                    const userExercise = getExerciseById(ex.id);
                    ex.response = userExercise.response;

                    if (block.exerciseType === 'match') {
                        // todo: hacky solution so that match block items have a `correct` key
                        ex.correct = userExercise.response.length === ex.pairs.length;

                        ex.pairs.forEach(pair => {
                            pair.correct = userExercise.response.some(r =>
                                r.start === pair.start && r.end === pair.end
                            );
                        });

                    } else if (block.exerciseType === 'input') {
                        ex.correct = ex.answers.some(a =>
                            a.trim().toLowerCase() === userExercise.response?.trim().toLowerCase()
                        );

                    } else if (block.exerciseType === 'select') {
                        ex.correct = ex.answerId === userExercise.response;

                    } else if (block.exerciseType === 'sort') {
                        const correctOrder = ex.items.map(item => item.id);
                        const userOrder = userExercise.response.map(item => item.id);

                        ex.correct =
                            correctOrder.length === userOrder.length &&
                            correctOrder.every((id, index) => id === userOrder[index]);
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
