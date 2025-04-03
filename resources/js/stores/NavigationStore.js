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

    const themes = ['palweb'];

    const activeTheme = ref(localStorage.getItem('selectedTheme') || 'palweb');

    const updateTheme = (theme) => {
        document.body.classList.remove(...themes.map((t) => `theme-${t}`));
        document.body.classList.add(`theme-${theme}`);
        activeTheme.value = theme;
        localStorage.setItem('selectedTheme', theme);
    };

    updateTheme(activeTheme.value);

    return {
        data,
        themes,
        activeTheme,
        showSignIn,
        showSignUp,
        showSendFeedback,
        updateTheme,
        toggleSidebar,
        closeSidebar,
    };
});
