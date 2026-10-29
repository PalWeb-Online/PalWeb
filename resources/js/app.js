import {createApp} from 'vue';
import {createPinia} from 'pinia';
import App from './App.vue';
import i18n from './i18n';
import router from './router.js';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import {registerSW} from 'virtual:pwa-register';
import 'vue3-carousel/dist/carousel.css';
import {useUserStore} from './stores/UserStore.js';
import {bootstrapHttp} from './http.js';
import {
    flushUserInterfacePreferenceSync,
    initializeUserInterfacePreferences,
    reconcileUserInterfacePreferencesWithUser,
} from './runtime/preferences.js';

registerSW({
    immediate: true,
    onNeedRefresh() {
        window.location.reload();
    },
    onRegisteredSW(_swUrl, registration) {
        if (!registration) {
            return;
        }

        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                registration.update();
            }
        });

        setInterval(() => {
            registration.update();
        }, 60 * 60 * 1000);
    },
});

const pinia = createPinia();

const httpReady = bootstrapHttp().catch((error) => {
    console.error('Failed to bootstrap HTTP:', error);
});

initializeUserInterfacePreferences(i18n);

const syncUserInterfacePreferences = async (user = null) => {
    const result = await flushUserInterfacePreferenceSync(i18n, user);

    if (result.theme?.preferences && user) {
        useUserStore(pinia).setUser({
            ...user,
            preferences: result.theme.preferences,
        });
    }

    return result;
};

const bootstrapCurrentUser = async () => {
    try {
        await httpReady;

        const user = await useUserStore(pinia).fetchCurrentUser();

        reconcileUserInterfacePreferencesWithUser(i18n, user);

        syncUserInterfacePreferences(user).catch((error) => {
            console.error('Failed to sync interface preferences:', error);
        });

    } catch (error) {
        console.error('Failed to bootstrap the current user:', error);
    }
};

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

router.beforeEach((to) => {
    if (navigator.onLine) {
        return;
    }

    if (to.name === 'offline') {
        return;
    }

    return {
        name: 'offline',
        replace: true,
    };
});

window.addEventListener('online', () => {
    syncUserInterfacePreferences(useUserStore(pinia).user).catch((error) => {
        console.error('Failed to sync interface preferences:', error);
    });
});

createApp(App)
    .use(i18n)
    .use(pinia)
    .use(router)
    .mount('#app');

bootstrapCurrentUser();
