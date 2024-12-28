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
                value: 'terms'
            },
            {
                label: 'Sentences',
                value: 'sentences'
            },
            {
                label: 'Decks',
                value: 'decks'
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

        openSearchGenie() {
            const triggerButtons = document.querySelectorAll('.sg-trigger');
            triggerButtons.forEach((button) => {
                button.classList.add('active');
            });

            this.isOpen = true;
            document.body.style.overflow = 'hidden';
        },

        closeSearchGenie() {
            this.isOpen = false;
            this.searchTerm = '';
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
