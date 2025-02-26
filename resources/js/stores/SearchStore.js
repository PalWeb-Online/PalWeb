import {defineStore} from 'pinia';
import axios from 'axios';

export const useSearchStore = defineStore('SearchStore', {
    state: () => ({
        isOpen: false,
        action: 'search',
        activeModel: 'terms',
        selectedModel: null,
        filters: {
            search: '',
            category: '',
            attribute: '',
            form: '',
            singular: '',
            plural: ''
        },
        searchResults: {
            terms: [],
            sentences: [],
            decks: [],
        },
        tabs: [
            {
                label: 'Terms',
                value: 'terms',
                disabled: false,
            },
            {
                label: 'Sentences',
                value: 'sentences',
                disabled: false,
            },
            {
                label: 'Decks',
                value: 'decks',
                disabled: false,
            },
        ],
    }),

    actions: {
        openSearchGenie() {
            const triggerButtons = document.querySelectorAll('.sg-trigger');
            triggerButtons.forEach((button) => {
                button.classList.add('active');
            });

            this.tabs = this.tabs.map(tab => {
                if (this.queue === 'record') {
                    this.activeModel = 'decks';
                    return {
                        ...tab,
                        disabled: tab.value !== 'decks',
                    };
                } else if (this.queue === 'viewer') {
                    this.activeModel = 'decks';
                    return {
                        ...tab,
                        disabled: tab.value !== 'decks',
                    };
                } else if (this.queue === 'builder') {
                    this.activeModel = 'terms';
                    return {
                        ...tab,
                        disabled: tab.value !== 'terms',
                    };
                } else if (this.queue === 'dialogger') {
                    this.activeModel = 'sentences';
                    return {
                        ...tab,
                        disabled: tab.value !== 'sentences',
                    };
                }

                return {...tab, disabled: false};
            });

            this.isOpen = true;
            document.body.style.overflow = 'hidden';
        },

        closeSearchGenie() {
            this.isOpen = false;
            this.activeModel = 'terms';
            this.filters = {
                search: '',
                category: '',
                attribute: '',
                form: '',
                singular: '',
                plural: ''
            };
            this.searchResults = {
                terms: [],
                sentences: [],
                decks: []
            };

            document.body.style.overflow = '';

            const triggerButtons = document.querySelectorAll('.sg-trigger');
            triggerButtons.forEach((button) => {
                button.classList.remove('active');
            });
        },

        updateFilter(key, value) {
            this.filters[key] = value;
            this.search();
        },

        async search() {
            if (!this.filters['search'] && Object.values(this.filters).every(value => !value)) {
                this.searchResults = {
                    terms: [],
                    sentences: [],
                    decks: []
                };
                return;
            }

            try {
                const response = await axios.post('/search', {
                    search: this.filters['search'] || '',
                    ...this.filters,
                });
                this.searchResults = response.data;

            } catch (error) {
                console.error('Search error:', error);
                this.searchResults = {
                    terms: [],
                    sentences: [],
                    decks: []
                };
            }
        },

        setAction(action) {
            this.action = action;
        },

        resetAction() {
            this.action = 'search';
        },

        selectModel(model) {
            this.selectedModel = model;
        },

        deselectModel() {
            this.selectedModel = null;
        },
    },
});
