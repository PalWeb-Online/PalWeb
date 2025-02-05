import { createApp, h } from "vue/dist/vue.esm-bundler";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from '@inertiajs/progress'
import { createPinia } from 'pinia';
import axios from 'axios';
import Alpine from 'alpinejs';
import {Head, Link} from '@inertiajs/inertia-vue3'
import TermEditor from "./components/TermEditor.vue";
import TermHead from "./components/TermHead.vue";
import TermItem from "./components/TermItem.vue";
import DeckItem from "./components/DeckItem.vue";
import DeckContainer from "./components/DeckContainer.vue";
import DeckFlashcard from "./components/DeckFlashcard.vue";
import SentenceContainer from "./components/SentenceContainer.vue";
import DialogItem from "./components/DialogItem.vue";
import DialogContainer from "./components/DialogContainer.vue";
import BadgeItem from "./components/BadgeItem.vue";
import PrivacyToggleButton from "./components/PrivacyToggleButton.vue";

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

const multiMountComponents = [
    { selector: '[data-vue-component="TermItem"]', component: TermItem },
    { selector: '[data-vue-component="TermHead"]', component: TermHead },
    { selector: '[data-vue-component="DeckItem"]', component: DeckItem },
    { selector: '[data-vue-component="DeckFlashcard"]', component: DeckFlashcard },
    { selector: '[data-vue-component="DeckContainer"]', component: DeckContainer },
    { selector: '[data-vue-component="SentenceContainer"]', component: SentenceContainer },
    { selector: '[data-vue-component="DialogItem"]', component: DialogItem },
    { selector: '[data-vue-component="DialogContainer"]', component: DialogContainer },
    { selector: '[data-vue-component="BadgeItem"]', component: BadgeItem },
    { selector: '[data-vue-component="PrivacyToggleButton"]', component: PrivacyToggleButton },
];

function mountMultiComponents(selector, component) {
    document.querySelectorAll(selector).forEach(element => {
        const propsData = JSON.parse(element.dataset.props || '{}');

        const app = createApp({
            render: () => h(component, propsData),
        });

        app.use(pinia);
        app.mount(element);
    });
}

multiMountComponents.forEach(({ selector, component }) => {
    mountMultiComponents(selector, component);
});

// TODO: Simplify the following initializations as well.

if (document.querySelector('#termEditor')) {
    const termEditorApp = createApp({});
    termEditorApp.component('TermEditor', TermEditor);
    termEditorApp.mount('#termEditor');
}
