import {defineStore} from 'pinia';
import {reactive} from "vue";
import axios from 'axios';

export const useSearchStore = defineStore('SearchStore', () => {
    const data = reactive({
        isOpen: false,
        action: 'search',
        activeModel: 'terms',
        enabledModel: 'terms',
        selectedModel: null,
        filters: {
            search: '',
            match: 'term',
            pinned: 0,
            category: '',
            attribute: '',
            form: '',
            singular: '',
            plural: ''
        },
        results: {
            terms: [],
            sentences: [],
            decks: [],
        },
    });

    const tabs = reactive({
        terms: {label: 'terms', disabled: false},
        decks: {label: 'decks', disabled: false},
        sentences: {label: 'sentences', disabled: false},
    });

    let cancelTokenSource = null;

    const openSearchGenie = (action = 'search', enabledModel = null) => {
        data.action = action;
        data.activeModel = enabledModel || 'terms';

        Object.values(tabs).forEach((tab) => {
            tab.disabled = enabledModel ? tab.label !== enabledModel : false;
        });

        data.isOpen = true;
    };

    const resetSearchGenie = () => {
        data.activeModel = 'terms';
        data.filters = {
            search: '',
            match: 'term',
            pinned: 0,
            category: '',
            attribute: '',
            form: '',
            singular: '',
            plural: ''
        };
        data.results = {
            terms: [],
            sentences: [],
            decks: []
        };
    }

    const search = async (key, value) => {
        data.filters[key] = value;

        if (!data.filters['search'] && Object.values(data.filters).every(value => !value)) {
            data.results = {
                terms: [],
                sentences: [],
                decks: []
            };
            return;
        }

        if (cancelTokenSource) {
            cancelTokenSource.cancel("Search canceled because a new request was made.");
        }

        cancelTokenSource = axios.CancelToken.source();

        try {
            const response = await axios.post('/search', {
                    search: data.filters['search'] || '',
                    ...data.filters,
                }, {
                    cancelToken: cancelTokenSource.token,
                }
            );
            data.results = response.data;

        } catch (error) {
            console.error('Search error:', error);
            data.results = {
                terms: [],
                sentences: [],
                decks: []
            };
        }
    };

    const setAction = (action) => {
        data.action = action;
    };

    const resetAction = () => {
        data.action = 'search';
    };

    const selectModel = (model) => {
        data.selectedModel = model;
    };

    const deselectModel = () => {
        data.selectedModel = null;
    };

    return {
        data,
        tabs,
        openSearchGenie,
        resetSearchGenie,
        search,
        setAction,
        resetAction,
        selectModel,
        deselectModel
    }
});
