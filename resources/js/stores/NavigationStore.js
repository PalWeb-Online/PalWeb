import {defineStore} from 'pinia';
import {reactive, ref, watch} from "vue";
import {useUserStore} from "./UserStore.js";
import {
    changeApplicationTheme,
    initializeClientTheme,
    reconcileThemeWithUser,
    themes as availableThemes,
} from '../runtime/preferences.js';

export const useNavigationStore = defineStore('NavigationStore', () => {
    const UserStore = useUserStore();

    const data = reactive({
        section: 'home',
        isOpen: false,
    });

    const showSignIn = ref(false);
    const showSignUp = ref(false);
    const showSendFeedback = ref(false);

    const toggleSidebar = () => {
        data.isOpen = !data.isOpen;
        document.body.style.overflow = data.isOpen ? 'hidden' : ''
    }

    const closeSidebar = () => {
        data.isOpen = false;
        document.body.style.overflow = ''
    }

    const themes = availableThemes;
    const activeTheme = ref(initializeClientTheme());

    const updateTheme = async (theme) => {
        const result = await changeApplicationTheme(theme, UserStore.user);

        activeTheme.value = result.theme;

        if (result.preferences && UserStore.user) {
            const currentUser = UserStore.user;

            UserStore.setUser({
                ...currentUser,
                preferences: result.preferences,
            });
        }
    };

    watch(
        () => UserStore.user?.preferences?.theme,
        (theme) => {
            activeTheme.value = reconcileThemeWithUser({
                preferences: {
                    theme,
                },
            });
        },
    );

    return {
        data,
        themes,
        activeTheme,
        updateTheme,
        showSignIn,
        showSignUp,
        showSendFeedback,
        toggleSidebar,
        closeSidebar,
    };
});
