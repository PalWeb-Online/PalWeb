import {defineStore} from 'pinia';
import {reactive, ref} from "vue";

export const useNavigationStore = defineStore('NavigationStore', () => {
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

    const themes = ['PalWebOS', 'Watermelon', 'Nabatean', 'Jerusalem', 'Falastin'];
    const activeTheme = ref(localStorage.getItem('selectedTheme') || 'Falastin');

    const updateTheme = (theme) => {
        document.body.classList.remove(...themes.map((t) => `theme-${t}`));
        document.body.classList.add(`theme-${theme}`);
        activeTheme.value = theme;
        localStorage.setItem('selectedTheme', theme);
    };

    updateTheme(activeTheme.value);

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
