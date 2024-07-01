<script>
import debounce from 'lodash/debounce';

export default {
    props: {
        resultType: {
            type: String,
            default: 'link'
        },
    },

    components: {
        debounce,
    },

    data() {
        return {
            searchTerm: new URLSearchParams(window.location.search).get('search') || '',
            searchResults: [],
            showResults: false,
            ignoreNextSearch: false,
        };
    },
    created() {
        this.debouncedSearch = debounce(this.liveResults, 150);
        document.addEventListener("click", this.closeResults);
    },
    beforeDestroy() {
        document.removeEventListener("click", this.closeResults);
    },
    watch: {
        searchTerm(newVal) {
            this.debouncedSearch(newVal);
        }
    },
    methods: {
        liveResults(searchTerm) {
            if (typeof searchTerm === 'string') {
                axios.post(`/dictionary/search`, {searchTerm})
                    .then(response => {
                        if (this.ignoreNextSearch) {
                            this.ignoreNextSearch = false;
                        } else {
                            this.showResults = true;
                        }
                        this.searchResults = response.data.searchResults;
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                    });
            }
        },

        closeResults() {
            this.showResults = false;
        },

        emitSlug(term) {
            this.searchTerm = term.term;
            this.$emit('emitSlug', term.slug);
            this.ignoreNextSearch = true;
            this.closeResults();
        }
    }
};
</script>

<template>
    <div class="search-bar">
        <input v-model="searchTerm" @change="liveResults" @click.stop
               name="search" placeholder="دوّر" type="text" autocomplete="off"/>
        <div v-if="searchResults.length > 0 && showResults" class="search-results" @click.stop>
            <a v-if="resultType === 'link'" :href="'/dictionary/terms/'+value.slug" class="search-result"
               v-for="(value, index) in searchResults" :key="index">
                <div>{{ value.term }}</div>
                <div>({{ value.translit }}) {{ value.category }}.</div>
            </a>
            <div v-if="resultType === 'slug'" class="search-result" @click="emitSlug(value)"
                 v-for="(value, index) in searchResults" :key="index">
                <div>{{ value.term }}</div>
                <div>({{ value.translit }}) {{ value.category }}.</div>
            </div>
        </div>
        <button class="search" type="submit">
            <img src="/img/search.svg" alt="Search"/>
        </button>
    </div>
</template>
