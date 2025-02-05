import {defineStore} from 'pinia';
import {computed, reactive} from 'vue';

export const useUserStore = defineStore('UserStore', () => {
    const user = reactive(window.Laravel?.user || null);

    const roles = computed(() =>
        user?.roles?.map(role => role.name) || []
    );

    const isUser = computed(() => !!user);

    const isAdmin = computed(() =>
        roles.value.some(role => role === 'admin')
    );

    return {
        user,
        isUser,
        isAdmin,
    };
});
