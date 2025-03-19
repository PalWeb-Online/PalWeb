import { createApp, h } from "vue/dist/vue.esm-bundler";
import { createInertiaApp } from "@inertiajs/vue3";
import { InertiaProgress } from '@inertiajs/progress'
import { createPinia } from 'pinia';
import axios from 'axios';
import Alpine from 'alpinejs';
import {Head, Link} from '@inertiajs/vue3'

const pinia = createPinia();

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Alpine = Alpine;
Alpine.start();

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
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
