import {defineStore} from 'pinia';
import {computed, ref, watch} from 'vue';
import {useUserStore} from "./UserStore.js";

export const useAcademyStateStore = defineStore('AcademyStateStore', () => {
    const UserStore = useUserStore();

    const isLoading = ref(false);
    const hasError = ref(false);
    const fetchPromise = ref(null);
    const academyState = ref(null);

    const studyPlan = computed(() => academyState.value?.study_plan);
    const currentLesson = computed(() => academyState.value?.current_lesson ?? null);
    const lessonProgress = computed(() => academyState.value?.lesson_progress);
    const unlockedLessons = computed(() => academyState.value?.unlocked_lessons ?? UserStore.user?.unlocked_lessons ?? []);
    const stateVersion = ref(0);

    const idsMatch = (left, right) => String(left) === String(right);

    const today = () => {
        const date = new Date();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        return `${date.getFullYear()}-${month}-${day}`;
    };

    const hasCurrentStudyPlan = () => studyPlan.value?.date === today();

    const resetState = () => {
        stateVersion.value += 1;
        academyState.value = null;
        fetchPromise.value = null;
        isLoading.value = false;
        hasError.value = false;
    };

    const fetchAcademyState = async ({force = false} = {}) => {
        if (fetchPromise.value) {
            return fetchPromise.value;
        }

        if (!force && hasCurrentStudyPlan()) {
            return academyState.value;
        }

        isLoading.value = true;
        hasError.value = false;

        const requestVersion = stateVersion.value;

        const requestPromise = axios.get('/api/academy/state')
            .then(({data}) => {
                if (requestVersion !== stateVersion.value) {
                    return academyState.value;
                }

                academyState.value = data;

                return data;
            })
            .catch((error) => {
                if (requestVersion !== stateVersion.value) {
                    return academyState.value;
                }

                hasError.value = true;
                console.error('Unable to load Academy state:', error);

                throw error;
            })
            .finally(() => {
                if (fetchPromise.value === requestPromise) {
                    isLoading.value = false;
                    fetchPromise.value = null;
                }
            });

        fetchPromise.value = requestPromise;

        return fetchPromise.value;
    };

    const ensureState = () => fetchAcademyState();
    const refreshState = () => fetchAcademyState({force: true});

    const isCurrentLesson = (lessonId) => {
        return currentLesson.value?.id !== null && idsMatch(currentLesson.value?.id, lessonId);
    };

    const hasUnlockedLesson = (lessonId) => {
        return unlockedLessons.value?.some((id) => idsMatch(id, lessonId)) ?? false;
    };

    const isCompletedLesson = (lessonId, fallback = false) => {
        return lessonProgress.value?.[lessonId]?.completed ?? fallback;
    };

    watch(
        () => UserStore.user?.id,
        (newId, oldId) => {
            if (newId !== oldId) {
                resetState();
            }
        }
    );

    return {
        studyPlan,
        currentLesson,
        lessonProgress,
        unlockedLessons,
        isLoading,
        hasError,
        ensureState,
        refreshState,
        resetState,
        isCurrentLesson,
        hasUnlockedLesson,
        isCompletedLesson,
    };
});
