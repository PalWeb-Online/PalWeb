import { createApp, h } from "vue/dist/vue.esm-bundler";
import { createInertiaApp } from "@inertiajs/vue3";
import { InertiaProgress } from '@inertiajs/progress'
import { createPinia } from 'pinia';
import axios from 'axios';
// import Alpine from 'alpinejs';
import {Head, Link} from '@inertiajs/vue3'

const pinia = createPinia();

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// window.Alpine = Alpine;
// Alpine.start();

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
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

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue')
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .component("Link", Link)
            .component("Head", Head)
            .use(pinia)
            .mount(el);
    },
    title: title => "PalWeb | " + title
});

InertiaProgress.init({
    delay: 250,
    color: '#ffc32c',
    includeCSS: true,
    showSpinner: false,
})
