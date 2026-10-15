import {computed, unref} from "vue";

export function useUser(user) {
    const resolvedUser = computed(() => unref(user) ?? null);

    const isAdmin = computed(() => resolvedUser.value?.roles?.includes('admin') ?? false);
    const isStudent = computed(() => resolvedUser.value?.roles?.includes('student') ?? false);
    const isUser = computed(() => !!resolvedUser.value);

    const highestRole = computed(() => {
        if (isAdmin.value) return 'admin';
        if (isStudent.value) return 'student';
        if (isUser.value) return 'pal';
        return 'guest';
    });

    return {isAdmin, isStudent, isUser, highestRole, user: resolvedUser};
}
