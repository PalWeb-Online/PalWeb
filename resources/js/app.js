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
import {bootstrapHttp} from './runtime/bootstrap.js';
import {
    flushLocalePreferenceSync, initializeClientLocale, reconcileLocaleWithUser,
} from './runtime/locale.js';

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

// todo: merge into this file?
await bootstrapHttp();
initializeClientLocale(i18n);

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

const app = createApp(App)
    .use(i18n)
    .use(pinia);

try {
    const user = await useUserStore(pinia).fetchCurrentUser();
    reconcileLocaleWithUser(i18n, user);
    flushLocalePreferenceSync(i18n).catch((error) => {
        console.error('Failed to sync locale preference:', error);
    });

} catch (error) {
    console.error('Failed to bootstrap the current user:', error);
}

window.addEventListener('online', () => {
    flushLocalePreferenceSync(i18n).catch((error) => {
        console.error('Failed to sync locale preference:', error);
    });
});

app
    .use(router)
    .mount('#app');
