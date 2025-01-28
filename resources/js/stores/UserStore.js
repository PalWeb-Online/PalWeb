import {defineStore} from 'pinia';
import {ref, computed} from 'vue';

export const useUserStore = defineStore('UserStore', () => {
    const user = ref(window.Laravel?.user || null);

    const roles = computed(() =>
        user.value?.roles?.map(role => role.name) || []
    );

    const isUser = computed(() => !!user.value);

    const isAdmin = computed(() =>
        roles.value.some(role => role === 'admin')
    );

    return {
        user,
        isUser,
        isAdmin,
    };
});
