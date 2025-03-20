import {defineStore} from 'pinia';
import {computed} from 'vue';
import {usePage} from "@inertiajs/vue3";

export const useUserStore = defineStore('UserStore', () => {
    const page = usePage();
    const user = computed(() => page.props.auth.user || null);

    const isUser = computed(() => !!user.value);

    const isStudent = computed(() => user.value?.roles?.includes('student'));

    const isAdmin = computed(() => user.value?.roles?.includes('admin'));

    const highestRole = computed(() => {
        if (isAdmin.value) return 'admin';
        if (isStudent.value) return 'student';
        if (isUser.value) return 'pal';
        return 'guest';
    });

    return {
        user,
        isUser,
        isStudent,
        isAdmin,
        highestRole
    };
});
