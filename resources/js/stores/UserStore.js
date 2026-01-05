import {defineStore} from 'pinia';
import {computed, ref} from 'vue';
import {usePage} from "@inertiajs/vue3";
import {route} from 'ziggy-js';

export const useUserStore = defineStore('UserStore', () => {
    const page = usePage();
    const user = computed(() => page.props.auth.user || null);

    const decks = ref([]);
    const hasFetchedDecks = ref(false);

    const fetchDecks = async () => {
        if (!hasFetchedDecks.value) {
            try {
                const response = await axios.get(route('user.get-decks'));
                decks.value = response.data.decks || [];
                hasFetchedDecks.value = true;

            } catch (error) {
                console.error('Failed to fetch decks:', error);
            }
        }
    };

    const hasUnlockedLesson = (id) => {
        return user.value.unlocked_lessons.includes(id);
    }

    const isSuperuser = computed(() => user.value?.is_superuser);
    const isAdmin = computed(() => user.value?.roles?.includes('admin'));
    const isStudent = computed(() => user.value?.roles?.includes('student') || user.value?.roles?.includes('admin'));
    const isUser = computed(() => !!user.value);

    const highestRole = computed(() => {
        if (isAdmin.value) return 'admin';
        if (isStudent.value) return 'student';
        if (isUser.value) return 'pal';
        return 'guest';
    });

    return {
        user,
        decks,
        hasFetchedDecks,
        fetchDecks,
        hasUnlockedLesson,
        isSuperuser,
        isAdmin,
        isStudent,
        isUser,
        highestRole
    };
});
