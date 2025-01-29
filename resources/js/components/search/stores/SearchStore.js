import {defineStore} from 'pinia';
import axios from 'axios';

export const useSearchStore = defineStore('SearchStore', {
    state: () => ({
        isOpen: false,
        searchTerm: '',
        activeModel: 'terms',
        filters: {
            category: '',
            attribute: '',
            form: '',
            singular: '',
            plural: ''
        },
        filterOptions: {
            categories: [],
            attributes: [],
            forms: [],
            singularPatterns: [],
            pluralPatterns: [],
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
        async fetchFilterOptions() {
            try {
                const response = await axios.get('/search/filter-options');
                this.filterOptions = response.data;
            } catch (error) {
                console.error('Failed to fetch filter options:', error);
            }
        },

        updateFilter(key, value) {
            this.filters[key] = value;
            this.search();
        },

        openSearchGenie(context) {
            const triggerButtons = document.querySelectorAll('.sg-trigger');
            triggerButtons.forEach((button) => {
                button.classList.add('active');
            });

            this.tabs = this.tabs.map(tab => {
                if (context === 'record') {
                    this.activeModel = 'decks';
                    return {
                        ...tab,
                        disabled: tab.value !== 'decks',
                    };
                } else if (context === 'viewer') {
                    this.activeModel = 'decks';
                    return {
                        ...tab,
                        disabled: tab.value !== 'decks',
                    };
                } else if (context === 'builder') {
                    this.activeModel = 'terms';
                    return {
                        ...tab,
                        disabled: tab.value !== 'terms',
                    };
                } else if (context === 'dialogger') {
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
            this.searchTerm = '';
            this.activeModel = 'terms';
            this.filters = {
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

        async search() {
            if (!this.searchTerm && Object.values(this.filters).every(value => !value)) {
                this.searchResults = {
                    terms: [],
                    sentences: [],
                    decks: []
                };
                return;
            }

            try {
                const response = await axios.post('/search', {
                    search: this.searchTerm || '',
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
    },
});
