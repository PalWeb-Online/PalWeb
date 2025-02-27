<script setup>
import {onBeforeUnmount, onMounted, ref, watch} from 'vue';
import {flip, offset, shift, useFloating} from "@floating-ui/vue";
import debounce from 'lodash/debounce';
import axios from 'axios';

const props = defineProps({
    resultType: {
        type: String,
        default: 'link',
    },
});

const emit = defineEmits(['emitTerm']);

// Tooltip
const reference = ref(null);
const floating = ref(null);
const {floatingStyles} = useFloating(reference, floating, {
    placement: 'bottom',
    middleware: [offset(8), flip(), shift()],
});

// Search
const searchTerm = ref(new URLSearchParams(window.location.search).get('search') || '');
const searchResults = ref([]);
const showResults = ref(false);
const ignoreNextSearch = ref(false);

const debouncedSearch = debounce((term) => {
    liveResults(term);
}, 150);

const liveResults = (term) => {
    if (typeof term === 'string') {
        axios.post(`/dictionary/search`, {searchTerm: term})
            .then(response => {
                if (ignoreNextSearch.value) {
                    ignoreNextSearch.value = false;
                } else {
                    showResults.value = true;
                }
                searchResults.value = response.data.searchResults;
            })
            .catch(error => {
                console.error('Search error:', error);
            });
    }
};

const closeResults = () => {
    showResults.value = false;
};

const emitTerm = (term) => {
    emit('emitTerm', {term});
    ignoreNextSearch.value = true;
    searchTerm.value = '';
    closeResults();
};

onMounted(() => {
    document.addEventListener("click", closeResults);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", closeResults);
});

watch(searchTerm, (newVal) => {
    debouncedSearch(newVal);
});
</script>

<template>
    <div class="search-bar">
        <input ref="reference" v-model="searchTerm" @change="liveResults" @click.stop
               name="search" placeholder="دوّر" type="text" autocomplete="off"/>
        <div ref="floating" :style="floatingStyles" v-if="searchResults.length > 0 && showResults"
             class="search-results" @click.stop>
            <a v-if="resultType === 'link'" :href="'/dictionary/terms/'+value.slug" class="search-result"
               v-for="(value, index) in searchResults" :key="index">
                <div>{{ value.term }}</div>
                <div>({{ value.translit }}) {{ value.category }}.</div>
            </a>
            <div v-if="resultType === 'model'" class="search-result" @click="emitTerm(value)"
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
