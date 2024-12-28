<script setup>
import {useSearchStore} from './stores/SearchStore';
import {nextTick, onBeforeUnmount, onMounted, ref, watch} from 'vue';
import AppDialog from "../AppDialog.vue";
import TermFilters from "./TermFilters.vue";

const SearchStore = useSearchStore();
const activeIndex = ref(-1);
const searchInput = ref(null);

const exit = (event) => {
    if (event.target.classList.contains('app-dialog-overlay')) {
        SearchStore.closeSearchGenie();
    }
};

const scrollToActiveItem = () => {
    const container = document.querySelector('.sg-results');
    const activeItem = container?.querySelector('.sg-result-item.active');

    if (activeItem) {
        activeItem.scrollIntoView({
            behavior: 'smooth',
            block: 'nearest',
        });
    }
};

const setActiveModel = (model) => {
    activeIndex.value = -1;
    SearchStore.activeModel = model;
};

const openSearchGenie = () => {
    SearchStore.openSearchGenie();
    nextTick(() => {
        searchInput.value?.focus();
    });
}

onMounted(() => {
    const globalListener = (event) => {
        const isMac = navigator.platform.toUpperCase().indexOf('MAC') >= 0;

        if ((isMac && event.metaKey && event.key === 'k') || (!isMac && event.ctrlKey && event.key === 'k')) {
            event.preventDefault();
            if (SearchStore.isOpen) {
                SearchStore.closeSearchGenie();
            } else {
                openSearchGenie();
            }
        }

        if (event.type === 'open-search-genie') {
            if (!SearchStore.isOpen) {
                openSearchGenie();
            }
        }
    };

    const navigationListener = (event) => {
        if (!SearchStore.isOpen) return;

        const results = SearchStore.searchResults[SearchStore.activeModel] || [];
        if (!results.length) return;

        switch (event.key) {
            case 'ArrowDown':
                activeIndex.value = (activeIndex.value + 1) % results.length;
                scrollToActiveItem();
                break;
            case 'ArrowUp':
                activeIndex.value = (activeIndex.value - 1 + results.length) % results.length;
                scrollToActiveItem();
                break;
            case 'Enter':
                if (activeIndex.value >= 0 && activeIndex.value < results.length) {
                    selectResult(results[activeIndex.value]);
                }
                break;
        }
    };

    window.addEventListener('keydown', globalListener);
    window.addEventListener('open-search-genie', globalListener);
    document.addEventListener('keydown', navigationListener);

    onBeforeUnmount(() => {
        window.removeEventListener('keydown', globalListener);
        window.removeEventListener('open-search-genie', globalListener);
        document.removeEventListener('keydown', navigationListener);
    });
});

watch(() => SearchStore.searchResults, () => {
    activeIndex.value = -1;
});
</script>

<template>
    <div class="sg-trigger-wrapper">
        <button class="sg-trigger" @click="openSearchGenie">
            <img src="/img/key_cmd.svg" alt="Command Key"/>
            <img src="/img/key_k.svg" alt="Letter K Key"/>
        </button>
        <img class="search" src="/img/search.svg" alt="Search"/>
    </div>

    <div v-if="SearchStore.isOpen" class="app-dialog-overlay" @click="exit">
        <div class="sg-container">
            <input
                ref="searchInput"
                v-model="SearchStore.searchTerm"
                type="text"
                placeholder="دوّر"
                @input="SearchStore.search"
            />

            <div class="sg-tabs">
                <button
                    v-for="tab in SearchStore.tabs"
                    :key="tab.value"
                    :class="{ active: SearchStore.activeModel === tab.value, persisting: tab.value === 'terms' && !Object.values(SearchStore.filters).every(value => !value) }"
                    @click="setActiveModel(tab.value)"
                >
                    {{ tab.label }}
                </button>
            </div>

            <TermFilters v-if="SearchStore.activeModel === 'terms' || !Object.values(SearchStore.filters).every(value => !value)"/>

            <div class="sg-results">
                <template v-if="SearchStore.activeModel === 'terms'">
                    <template v-if="SearchStore.searchResults.terms.length > 0">
                        <a
                            v-for="(term, index) in SearchStore.searchResults.terms"
                            :key="term.id"
                            class="sg-result-item term"
                            :class="{ active: activeIndex === index }"
                            @mouseover="activeIndex = index"
                            :href="`/dictionary/terms/${term.slug}`"
                        >
                            <div>{{ term.term }}</div>
                            <div>({{ term.translit }}) {{ term.category }}.</div>
                        </a>
                    </template>

                    <div v-else class="sg-empty">
                        Nothing yet.
                    </div>
                </template>
                <template v-if="SearchStore.activeModel === 'sentences'">
                    <a v-if="SearchStore.searchResults.sentences.length > 0"
                       v-for="(sentence, index) in SearchStore.searchResults.sentences"
                       :key="sentence.id"
                       class="sg-result-item sentence"
                       :class="{ active: activeIndex === index }"
                       @mouseover="activeIndex = index"
                       :href="`/dictionary/sentences/${sentence.id}`"
                    >
                        <div>{{ sentence.sentence }}</div>
                    </a>
                    <div v-else class="sg-empty">
                        Nothing yet.
                    </div>
                </template>
                <template v-if="SearchStore.activeModel === 'decks'">
                    <a
                        v-if="SearchStore.searchResults.decks.length > 0"
                        v-for="(deck, index) in SearchStore.searchResults.decks"
                        :key="deck.id"
                        class="sg-result-item deck"
                        :class="{ active: activeIndex === index }"
                        @mouseover="activeIndex = index"
                        :href="`/community/decks/${deck.id}`"
                    >
                        <div>{{ deck.name }}</div>
                    </a>
                    <div v-else class="sg-empty">
                        Nothing yet.
                    </div>
                </template>
            </div>
            <div class="sg-all-results"
                 v-if="SearchStore.activeModel === 'terms' && SearchStore.searchResults.terms.length > 0">
                <a :href="`/dictionary/terms?search=${encodeURIComponent(SearchStore.searchTerm)}&category=${SearchStore.filters.category}&attribute=${SearchStore.filters.attribute}&form=${SearchStore.filters.form}&singular=${SearchStore.filters.singular}&plural=${SearchStore.filters.plural}`">
                    See All Results
                </a>
            </div>
            <div class="sg-all-results"
                 v-if="SearchStore.activeModel === 'sentences' && SearchStore.searchResults.sentences.length > 0">
                <a :href="`/dictionary/sentences?search=${encodeURIComponent(SearchStore.searchTerm)}&category=${SearchStore.filters.category}&attribute=${SearchStore.filters.attribute}&form=${SearchStore.filters.form}&singular=${SearchStore.filters.singular}&plural=${SearchStore.filters.plural}`">
                    See All Results
                </a>
            </div>
            <div class="sg-all-results"
                 v-if="SearchStore.activeModel === 'decks' && SearchStore.searchResults.decks.length > 0">
                <a :href="`/community/decks?search=${encodeURIComponent(SearchStore.searchTerm)}&category=${SearchStore.filters.category}&attribute=${SearchStore.filters.attribute}&form=${SearchStore.filters.form}&singular=${SearchStore.filters.singular}&plural=${SearchStore.filters.plural}`">
                    See All Results
                </a>
            </div>

            <div class="sg-footer">
                <div><b>Enter</b> selects</div>
                <div><b>Up/Dwn</b> navigates</div>
                <div><b>Ctrl+K</b> toggles Search Genie</div>
            </div>

            <AppDialog title="Search Genie" size="large">
                <template #trigger>
                    <img alt="Info" src="/img/idea.svg"/>
                </template>
                <template #content>
                    <p>Welcome to the <b>Search Genie</b>!</p>
                    <p>The <b>Search Genie</b> will match your search with Terms, Sentences & Decks on PalWeb. It does
                        so by matching your search with a variety of attributes on the items you are searching for.</p>
                    <div>Terms are found if ...</div>
                    <ul>
                        <li>
                            (typing in Arabic script) the search string matches the Arabic term or any of its
                            alternative spellings, or the default spelling of any of its listed inflections;
                        </li>
                        <li>(typing in Latin script) the search string matches the transliteration of any of its
                            pronunciations, or the default transliteration of any of its listed inflections — or any
                            word in any of its definitions.
                        </li>
                    </ul>
                    <div>Sentences are found if ...</div>
                    <ul>
                        <li>the Sentence contains a Term matching the search, based on the above criteria.</li>
                    </ul>
                    <div>Decks are found if ...</div>
                    <ul>
                        <li>the search string matches the Deck's title, or</li>
                        <li>the Deck contains a Term matching the search, based on the above criteria.</li>
                    </ul>
                </template>
            </AppDialog>
        </div>
    </div>
</template>
