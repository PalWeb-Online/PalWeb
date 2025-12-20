import {createApp, h} from "vue/dist/vue.esm-bundler";
import {createInertiaApp, Head, Link} from "@inertiajs/vue3";
import {createPinia} from 'pinia';
import axios from 'axios';
import i18n from './i18n';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

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

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', {eager: true})
        return pages[`./Pages/${name}.vue`]
    },
    setup({el, App, props, plugin}) {
        i18n.global.locale.value = props.initialPage.props.locale;

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
