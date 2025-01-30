import { createApp, h } from "vue/dist/vue.esm-bundler";
import TermEditor from "./components/TermEditor.vue";
import TermHead from "./components/TermHead.vue";
import TermItem from "./components/TermItem.vue";
import DeckItem from "./components/DeckItem.vue";
import DeckContainer from "./components/DeckContainer.vue";
import DeckFlashcard from "./components/DeckFlashcard.vue";
import SentenceItem from "./components/SentenceItem.vue";
import DialogItem from "./components/DialogItem.vue";
import DialogContainer from "./components/DialogContainer.vue";
import BadgeItem from "./components/BadgeItem.vue";
import PrivacyToggleButton from "./components/PrivacyToggleButton.vue";
import SearchGenie from './components/search/SearchGenie.vue';
import DeckBuilder from "./components/decks/DeckBuilder.vue";
import CardViewer from "./components/decks/CardViewer.vue";
import Dialogger from "./components/dialogs/Dialogger.vue";
import RecordWizard from './components/record/RecordWizard.vue';

import axios from 'axios';
import Alpine from 'alpinejs';

import { createPinia } from 'pinia';
const pinia = createPinia();

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Alpine = Alpine;
Alpine.start();

const multiMountComponents = [
    { selector: '[data-vue-component="TermItem"]', component: TermItem },
    { selector: '[data-vue-component="TermHead"]', component: TermHead },
    { selector: '[data-vue-component="DeckItem"]', component: DeckItem },
    { selector: '[data-vue-component="DeckFlashcard"]', component: DeckFlashcard },
    { selector: '[data-vue-component="DeckContainer"]', component: DeckContainer },
    { selector: '[data-vue-component="SentenceItem"]', component: SentenceItem },
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

if (document.querySelector('#searchGenie')) {
    const searchGenieApp = createApp(SearchGenie);
    searchGenieApp.use(pinia);
    searchGenieApp.mount('#searchGenie');
}

if (document.querySelector('#recordWizard')) {
    const recordWizardApp = createApp(RecordWizard);
    recordWizardApp.use(pinia);
    recordWizardApp.mount('#recordWizard');
}

if (document.querySelector('#cardViewer')) {
    const cardViewerApp = createApp(CardViewer);
    cardViewerApp.use(pinia);
    cardViewerApp.mount('#cardViewer');
}

if (document.querySelector('#deckBuilder')) {
    const deckBuilderApp = createApp(DeckBuilder);
    deckBuilderApp.use(pinia);
    deckBuilderApp.mount('#deckBuilder');
}

if (document.querySelector('#dialogger')) {
    const dialoggerApp = createApp(Dialogger);
    dialoggerApp.use(pinia);
    dialoggerApp.mount('#dialogger');
}

// TODO: Simplify the following initializations as well.

if (document.querySelector('#termEditor')) {
    const termEditorApp = createApp({});
    termEditorApp.component('TermEditor', TermEditor);
    termEditorApp.mount('#termEditor');
}
