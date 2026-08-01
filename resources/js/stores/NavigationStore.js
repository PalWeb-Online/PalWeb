import {defineStore} from 'pinia';
import {reactive, ref, watch} from "vue";
import {route} from "ziggy-js";
import {useUserStore} from "./UserStore.js";

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

    const themes = ['PalWebOS', 'Watermelon', 'Nabatean', 'Jerusalem', 'Falastin', 'Msaxxan'];
    const defaultTheme = 'Msaxxan';

    const isValidTheme = (theme) => themes.includes(theme);

    const userTheme = () => UserStore.user?.preferences?.theme;
    const storedTheme = () => localStorage.getItem('selectedTheme');

    const initialTheme = () => {
        if (isValidTheme(userTheme())) {
            return userTheme();
        }

        if (isValidTheme(storedTheme())) {
            return storedTheme();
        }

        return defaultTheme;
    };

    const activeTheme = ref(initialTheme());

    const applyTheme = (theme) => {
        const nextTheme = isValidTheme(theme) ? theme : defaultTheme;

        document.body.classList.remove(...themes.map((t) => `theme-${t}`));
        document.body.classList.add(`theme-${nextTheme}`);
        activeTheme.value = nextTheme;
        localStorage.setItem('selectedTheme', nextTheme);

        return nextTheme;
    };

    const updateTheme = async (theme) => {
        const nextTheme = applyTheme(theme);

        if (!UserStore.user) {
            return;
        }

        try {
            const {data} = await axios.patch(route('users.preferences.update'), {
                theme: nextTheme,
            });

            const currentUser = UserStore.user;

            UserStore.setUser({
                ...currentUser,
                preferences: data.preferences,
            });
        } catch (error) {
            console.error('Failed to save theme preference:', error);
        }
    };

    applyTheme(activeTheme.value);

    watch(
        () => UserStore.user?.preferences?.theme,
        (theme) => {
            if (isValidTheme(theme) && theme !== activeTheme.value) {
                updateTheme(theme);
            }
        }
    );

    const fontThemes = ['GoldStar', 'Banksy', 'Pal2K'];
    const activeFontTheme = ref(localStorage.getItem('selectedFontTheme') || 'GoldStar');

    const updateFontTheme = (theme) => {
        document.body.classList.remove(...fontThemes.map((t) => `font-theme-${t}`));
        document.body.classList.add(`font-theme-${theme}`);
        activeFontTheme.value = theme;
        localStorage.setItem('selectedFontTheme', theme);
    };

    updateFontTheme(activeFontTheme.value);

    return {
        data,
        themes,
        activeTheme,
        updateTheme,
        fontThemes,
        activeFontTheme,
        updateFontTheme,
        showSignIn,
        showSignUp,
        showSendFeedback,
        toggleSidebar,
        closeSidebar,
    };
});
