import {defineStore} from 'pinia';
import {computed, ref, watch} from 'vue';
import {usePage} from "@inertiajs/vue3";
import {route} from 'ziggy-js';
import {useNotificationStore} from "./NotificationStore.js";
import {useUser} from "../composables/users/useUser.js";

export const useUserStore = defineStore('UserStore', () => {
    const page = usePage();
    const user = computed(() => page.props.auth.user || null);

    const {isAdmin, isStudent, isUser, highestRole} = useUser(user);
    const isSuperuser = computed(() => !!user.value?.is_superuser);

    const NotificationStore = useNotificationStore();

    const decks = ref([]);
    const hasFetchedDecks = ref(false);

    const resetUserState = () => {
        decks.value = [];
        hasFetchedDecks.value = false;
    };

    watch(
        () => user.value?.id,
        async (newId, oldId) => {
            if (newId !== oldId) {
                resetUserState();
                await NotificationStore.refreshBrowserSubscriptionState();
            }
        }
    );

    const fetchDecks = async () => {
        if (!hasFetchedDecks.value) {
            try {
                const response = await axios.get(route('users.decks.get'));
                decks.value = response.data.decks || [];
                hasFetchedDecks.value = true;

            } catch (error) {
                console.error('Failed to fetch decks:', error);
            }
        }
    };

    const hasUnlockedLesson = (id) => {
        return user.value?.unlocked_lessons?.includes(id) ?? false;
    }

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
