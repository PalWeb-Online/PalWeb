import {inject, nextTick, provide, reactive, ref} from "vue";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {useScoreManager} from "../useScoreManager.js";
import {shuffle} from "lodash";

const activitySessionKey = Symbol("ActivitySession");

export function createActivitySession() {
    const scoreManager = useScoreManager();

    const activity = ref(null);
    const exercises = ref([]);

    const isLoadingSession = ref(false);
    const isViewingResults = ref(false);

    const getExerciseById = (id) => {
        return exercises.value.find(ex => ex.id === id);
    };

    const startActivity = () => {
        if (!activity.value?.document?.blocks) {
            exercises.value = [];
            isLoadingSession.value = false;
            return;
        }

        scoreManager.score.scorable_type = "activity";

        exercises.value = activity.value?.document?.blocks
            .filter(b => b.type === "exercises")
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

        isLoadingSession.value = false;
    };

    const submitActivity = () => {
        if (!activity.value?.document?.blocks) return;

        activity.value?.document?.blocks.forEach(block => {
            if (block.type === "exercises") {
                block.items.forEach(ex => {
                    const userExercise = getExerciseById(ex.id);
                    if (!userExercise) return;

                    ex.response = userExercise.response;

                    if (block.exerciseType === "match") {
                        ex.correct = userExercise.response.length === ex.pairs.length;

                        ex.pairs.forEach(pair => {
                            pair.correct = userExercise.response.some(r =>
                                r.start === pair.start && r.end === pair.end
                            );
                        });

                    } else if (block.exerciseType === "input") {
                        ex.correct = ex.answers.some(a =>
                            a.trim().toLowerCase() === userExercise.response?.trim().toLowerCase()
                        );

                    } else if (block.exerciseType === "select") {
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

        scoreManager.score.results = activity.value.document.blocks;
        scoreManager.calculateScore();

        isViewingResults.value = true;

        nextTick(() => {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });
    };

    const saveScore = () => {
        if (!activity.value) return;

        scoreManager.saveScore(activity.value?.id, {
            onSuccess: () => {
                router.get(route('lessons.show', activity.value.lesson.global_position));
            }
        });
    };

    const reset = () => {
        isLoadingSession.value = true;
        isViewingResults.value = false;
        activity.value = null;
        exercises.value = [];
        scoreManager.resetScore();
    };

    return reactive({
        activity,
        exercises,
        isViewingResults,
        isLoadingSession,
        score: scoreManager.score,
        isSaved: scoreManager.isSaved,
        getExerciseById,
        startActivity,
        submitActivity,
        markCorrect: scoreManager.markCorrect,
        saveScore,
        reset,
    });
}

export function provideActivitySession(session) {
    provide(activitySessionKey, session);
    return session;
}

export function useActivitySession() {
    const session = inject(activitySessionKey);

    if (!session) {
        throw new Error("useActivitySession must be used inside an Activity page provider.");
    }

    return session;
}
