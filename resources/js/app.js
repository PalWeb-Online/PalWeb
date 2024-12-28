import { createApp, h } from "vue/dist/vue.esm-bundler";
import TermEditor from "./components/TermEditor.vue";
import SentenceEditor from "./components/SentenceEditor.vue";
import DeckBuilder from "./components/DeckBuilder.vue";
import DictionaryFilters from "./components/DictionaryFilters.vue";
import TermHead from "./components/TermHead.vue";
import TermItem from "./components/TermItem.vue";
import DeckHead from "./components/DeckHead.vue";
import DeckItem from "./components/DeckItem.vue";
import DeckFlashcard from "./components/DeckFlashcard.vue";
import SentenceItem from "./components/SentenceItem.vue";
import BadgeItem from "./components/BadgeItem.vue";
import ContextActions from "./components/ContextActions.vue";
import PrivacyToggleButton from "./components/PrivacyToggleButton.vue";
import SearchGenie from './components/search/SearchGenie.vue';
import RecordWizard from './components/record/RecordWizard.vue';
import FlashcardViewer from "./components/flashcards/FlashcardViewer.vue";

import axios from 'axios';
import Alpine from 'alpinejs';

import { createPinia } from 'pinia';
const pinia = createPinia();

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Alpine = Alpine;
Alpine.start();

const multiMountComponents = [
    { selector: '[data-vue-component="ContextActions"]', component: ContextActions },
    { selector: '[data-vue-component="TermHead"]', component: TermHead },
    { selector: '[data-vue-component="TermItem"]', component: TermItem },
    { selector: '[data-vue-component="DeckHead"]', component: DeckHead },
    { selector: '[data-vue-component="DeckItem"]', component: DeckItem },
    { selector: '[data-vue-component="DeckFlashcard"]', component: DeckFlashcard },
    { selector: '[data-vue-component="SentenceItem"]', component: SentenceItem },
    { selector: '[data-vue-component="BadgeItem"]', component: BadgeItem },
    { selector: '[data-vue-component="PrivacyToggleButton"]', component: PrivacyToggleButton },
];

function mountMultiComponents(selector, component) {
    document.querySelectorAll(selector).forEach(element => {
        const propsData = JSON.parse(element.dataset.props || '{}');
        createApp({
            render: () => h(component, propsData)
        }).mount(element);
    });
}

multiMountComponents.forEach(({ selector, component }) => {
    mountMultiComponents(selector, component);
});

if (document.querySelector('#search-genie')) {
    const searchGenieApp = createApp(SearchGenie);
    searchGenieApp.use(pinia);
    searchGenieApp.mount('#search-genie');
}

if (document.querySelector('#recordWizard')) {
    const recordWizardApp = createApp(RecordWizard);
    recordWizardApp.use(pinia);
    recordWizardApp.mount('#recordWizard');
}

if (document.querySelector('#flashcardViewer')) {
    const flashcardViewerApp = createApp(FlashcardViewer);
    flashcardViewerApp.use(pinia);
    flashcardViewerApp.mount('#flashcardViewer');
}

// TODO: Simplify the following initializations as well.

if (document.querySelector('#termEditor')) {
    const termEditorApp = createApp({});
    termEditorApp.component('TermEditor', TermEditor);
    termEditorApp.mount('#termEditor');
}

if (document.querySelector('#sentenceEditor')) {
    const sentenceEditorApp = createApp({});
    sentenceEditorApp.component('SentenceEditor', SentenceEditor);
    sentenceEditorApp.mount('#sentenceEditor');
}

if (document.querySelector('#deckBuilder')) {
    const DeckBuilderApp = createApp({});
    DeckBuilderApp.component('DeckBuilder', DeckBuilder);
    DeckBuilderApp.mount('#deckBuilder');
}

if (document.querySelector('#dictionaryFilters')) {
    const dictionaryFiltersApp = createApp({});
    dictionaryFiltersApp.component('DictionaryFilters', DictionaryFilters);
    dictionaryFiltersApp.mount('#dictionaryFilters');
}
