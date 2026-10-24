import {defineStore} from 'pinia';
import {computed, ref, watch} from 'vue';
import {useNotificationStore} from "./NotificationStore.js";
import {useUser} from "../composables/users/useUser.js";

export const useUserStore = defineStore('UserStore', () => {
    const currentUser = ref(null);
    const hasFetchedCurrentUser = ref(false);

    const user = computed(() => currentUser.value);

    const {isAdmin, isStudent, isUser, highestRole} = useUser(user);
    const isSuperuser = computed(() => !!user.value?.is_superuser);

    const NotificationStore = useNotificationStore();

    const decks = ref([]);
    const hasFetchedDecks = ref(false);

    const resetUserState = () => {
        decks.value = [];
        hasFetchedDecks.value = false;
    };

    const setUser = (newUser) => {
        currentUser.value = newUser || null;
    };

    const clearUser = () => {
        setUser(null);
    };

    const fetchCurrentUser = async () => {
        if (hasFetchedCurrentUser.value) {
            return user.value;
        }

        try {
            const {data} = await axios.get('/api/auth/user');
            setUser(data.user || null);
            return user.value;

        } finally {
            hasFetchedCurrentUser.value = true;
        }
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
                const response = await axios.get('/api/auth/decks');
                decks.value = response.data.decks || [];
                hasFetchedDecks.value = true;

            } catch (error) {
                console.error('Failed to fetch decks:', error);
            }
        }
    };

    return {
        user,
        setUser,
        clearUser,
        fetchCurrentUser,
        hasFetchedCurrentUser,
        decks,
        hasFetchedDecks,
        fetchDecks,
        isSuperuser,
        isAdmin,
        isStudent,
        isUser,
        highestRole
    };
});
