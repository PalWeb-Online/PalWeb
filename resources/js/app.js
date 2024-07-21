import {createApp, h} from "vue/dist/vue.esm-bundler";
import TermEditor from "./components/TermEditor.vue";
import SentenceEditor from "./components/SentenceEditor.vue";
import DeckBuilder from "./components/DeckBuilder.vue";
import DictionaryFilters from "./components/DictionaryFilters.vue";
import SearchBar from "./components/SearchBar.vue";
import ActionButton from "./components/ActionButton.vue";

import axios from 'axios';
import Alpine from 'alpinejs';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

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

const searchBarElements = document.querySelectorAll('[data-vue-component="SearchBar"]');
searchBarElements.forEach((element, index) => {
    createApp({
        render: () => h(SearchBar)
    }).mount(element);
});

const actionButtonElements = document.querySelectorAll('[data-vue-component="ActionButton"]');
actionButtonElements.forEach((element, index) => {
    const propsData = JSON.parse(element.dataset.props);
    createApp({
        render: () => h(ActionButton, propsData)
    }).mount(element);
});

window.Alpine = Alpine;
Alpine.start();
