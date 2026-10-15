import {createApp} from "vue";
import {createPinia} from 'pinia';
import axios from 'axios';
import App from './App.vue';
import i18n from './i18n';
import router from './router.js';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import {registerSW} from 'virtual:pwa-register'
import 'vue3-carousel/dist/carousel.css';
import {syncCsrfToken} from "./utils/csrfToken.js";
import {useUserStore} from "./stores/UserStore.js";

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

window.axios = axios;
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.interceptors.response.use((response) => {
    const csrfToken = response?.data?.csrf_token;

    if (csrfToken) {
        syncCsrfToken(csrfToken);
    }

    return response;
});

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

i18n.global.locale.value = document.documentElement.lang || i18n.global.locale.value;
syncCsrfToken(document.querySelector('meta[name="csrf-token"]')?.content);

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
    await useUserStore(pinia).fetchCurrentUser();

} catch (error) {
    console.error('Failed to bootstrap the current user:', error);
}

app
    .use(router)
    .mount('#app');
