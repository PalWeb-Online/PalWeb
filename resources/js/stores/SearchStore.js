import {defineStore} from 'pinia';
import {reactive, ref} from "vue";
import axios from 'axios';

export const useSearchStore = defineStore('SearchStore', () => {
    const data = reactive({
        isOpen: false,
        action: 'search',
        activeModel: 'terms',
        selectedModel: null,
        filters: {
            search: '',
            match: 'term',
            sort: 'latest',
            pinned: false,
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

    const isLoading = ref(false);

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
            sort: 'latest',
            pinned: false,
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

        const filteredKeys = Object.keys(data.filters).filter(k => !['match', 'sort'].includes(k));
        const keysEmpty = filteredKeys.every(k => !data.filters[k]);

        if (keysEmpty) {
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

        isLoading.value = true;

        try {
            const response = await axios.post('/search', {
                    search: data.filters['search'] || '',
                    ...data.filters,
                }, {
                    cancelToken: cancelTokenSource.token,
                }
            );
            data.results = response.data;

            isLoading.value = false;

        } catch (error) {
            console.error('Search error:', error);
            data.results = {
                terms: [],
                sentences: [],
                decks: []
            };

            isLoading.value = false;
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
        isLoading,
        openSearchGenie,
        resetSearchGenie,
        search,
        setAction,
        resetAction,
        selectModel,
        deselectModel
    }
});
