import {createApp, h} from "vue/dist/vue.esm-bundler";
import {createInertiaApp, Head, Link, router} from "@inertiajs/vue3";
import {createPinia} from 'pinia';
import axios from 'axios';
import i18n from './i18n';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import { registerSW } from 'virtual:pwa-register'
import { route } from 'ziggy-js';

registerSW({immediate: true});
const pinia = createPinia();

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

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

function syncCsrfToken(token) {
    if (!token) {
        return;
    }

    let meta = document.querySelector('meta[name="csrf-token"]');

    if (!meta) {
        meta = document.createElement('meta');
        meta.name = 'csrf-token';
        document.head.appendChild(meta);
    }

    meta.setAttribute('content', token);
}

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', {eager: true})
        return pages[`./Pages/${name}.vue`]
    },
    setup({el, App, props, plugin}) {
        i18n.global.locale.value = props.initialPage.props.locale;

        syncCsrfToken(props.initialPage.props.csrfToken);

        router.on('success', (event) => {
            syncCsrfToken(event.detail.page.props.csrfToken);
        });

        createApp({render: () => h(App, props)})
            .use(plugin)
            .use(i18n)
            .component("Link", Link)
            .component("Head", Head)
            .use(pinia)
            .mount(el);
    },
    title: title => "PalWeb | " + title
});

router.on('before', (event) => {
    if (navigator.onLine) {
        return;
    }

    const url = event.detail.visit.url;

    if (typeof url === 'string' && url.includes(route('offline'))) {
        return;
    }

    event.preventDefault();

    router.visit(route('offline'), {
        replace: true,
        preserveState: true,
    });
});
