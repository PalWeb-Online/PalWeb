<script setup>
import {useSearchStore} from './stores/SearchStore';
import {nextTick, onBeforeUnmount, onMounted, ref, watch} from 'vue';
import AppDialog from "../AppDialog.vue";
import TermFilters from "./_TermFilters.vue";
import AppTooltip from "../AppTooltip.vue";
import {eventBus} from "../../utils/eventBus.js";

const SearchStore = useSearchStore();

const props = defineProps({
    context: {type: String, default: 'search'}
});

const emit = defineEmits([
    'emitTerm',
]);

const activeIndex = ref(-1);
const searchInput = ref(null);

const tooltip = ref(null);

function handleMouseMove(event) {
    let message = '';

    switch (props.context) {
        case "search":
            message = "Navigate to this item's page.";
            break;
        case "wizard":
            message = "Queue this item.";
            break;
        case "viewer":
            message = "Pin or Unpin this Deck.";
            break;
        case "builder":
            message = "Add this Term.";
            break;
    }

    tooltip.value.showTooltip(message, event);
}

function handleMouseLeave() {
    tooltip.value.hideTooltip();
}

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
    SearchStore.openSearchGenie(props.context);
    nextTick(() => {
        searchInput.value?.focus();
    });
}

const selectTerm = (term) => {
    if (props.context === 'builder') {
        emit('emitTerm', term);
    } else {
        window.location.href = `/dictionary/terms/${term.slug}`;
    }
};

const selectSentence = (sentence) => {
    window.location.href = `/dictionary/sentences/${sentence.id}`;
};

const selectDeck = async (deck) => {
    if (props.context === 'viewer') {
        try {
            const response = await axios.post('/community/decks/' + deck.id + '/pin');
            eventBus.emit('pinnedModel', {id: deck.id, model: 'deck', isPinned: response.data.isPinned});

        } catch (error) {
            console.error('Failed to Pin Deck.', error);
        }

    } else {
        window.location.href = `/community/decks/${deck.id}`;
    }
};

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
                    // todo: this won't work
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
                    :class="{
                        active: SearchStore.activeModel === tab.value,
                        persisting: tab.value === 'terms' && !Object.values(SearchStore.filters).every(value => !value),
                        disabled: tab.disabled
                    }"
                    @click="!tab.disabled && setActiveModel(tab.value)"
                >
                    {{ tab.label }}
                </button>
            </div>

            <TermFilters
                v-if="SearchStore.activeModel === 'terms' || !Object.values(SearchStore.filters).every(value => !value)"/>

            <div class="sg-results">
                <template v-if="SearchStore.activeModel === 'terms'">
                    <template v-if="SearchStore.searchResults.terms.length > 0">
                        <div
                            v-for="(term, index) in SearchStore.searchResults.terms"
                            :key="term.id"
                            :class="['sg-result-item term', { active: activeIndex === index }]"
                            @mousemove="handleMouseMove($event)"
                            @mouseleave="handleMouseLeave"
                            @mouseover="activeIndex = index"
                            @click="selectTerm(term)"
                        >
                            <div>{{ term.term }}</div>
                            <div>({{ term.translit }}) {{ term.category }}.</div>
                        </div>
                    </template>

                    <div v-else class="sg-empty">
                        Nothing yet.
                    </div>
                </template>
                <template v-if="SearchStore.activeModel === 'sentences'">
                    <div v-if="SearchStore.searchResults.sentences.length > 0"
                         v-for="(sentence, index) in SearchStore.searchResults.sentences"
                         :key="sentence.id"
                         :class="['sg-result-item sentence', { active: activeIndex === index }]"
                         @mousemove="handleMouseMove($event)"
                         @mouseleave="handleMouseLeave"
                         @mouseover="activeIndex = index"
                         @click="selectSentence(sentence)"
                    >
                        <div>{{ sentence.sentence }}</div>
                    </div>
                    <div v-else class="sg-empty">
                        Nothing yet.
                    </div>
                </template>
                <template v-if="SearchStore.activeModel === 'decks'">
                    <div
                        v-if="SearchStore.searchResults.decks.length > 0"
                        v-for="(deck, index) in SearchStore.searchResults.decks"
                        :key="deck.id"
                        :class="['sg-result-item deck', { active: activeIndex === index }]"
                        @mousemove="handleMouseMove($event)"
                        @mouseleave="handleMouseLeave"
                        @mouseover="activeIndex = index"
                        @click="selectDeck(deck)"
                    >
                        <div>{{ deck.name }}</div>
                    </div>
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

    <AppTooltip ref="tooltip"/>
</template>
