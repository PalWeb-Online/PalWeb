import {inject, nextTick, provide, reactive, ref} from "vue";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {useScoreManager} from "../useScoreManager.js";

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
            .flatMap(b => b.items.map(item => ({
                ...item,
                response: item.type === "match" ? [] : null,
            })));

        isLoadingSession.value = false;
    };

    const submitActivity = () => {
        if (!activity.value?.document?.blocks) return;

        activity.value?.document?.blocks.forEach(block => {
            if (block.type === "exercises") {
                block.items.forEach(item => {
                    const userExercise = getExerciseById(item.id);
                    if (!userExercise) return;

                    item.response = userExercise.response;

                    if (block.exerciseType === "match") {
                        item.correct = userExercise.response.length === item.pairs.length;

                        item.pairs.forEach(pair => {
                            pair.correct = userExercise.response.some(r =>
                                r.start === pair.start && r.end === pair.end
                            );
                        });

                    } else if (block.exerciseType === "input") {
                        item.correct = item.answers.some(a =>
                            a.trim().toLowerCase() === userExercise.response?.trim().toLowerCase()
                        );

                    } else if (block.exerciseType === "select") {
                        item.correct = item.answerId === userExercise.response;
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
