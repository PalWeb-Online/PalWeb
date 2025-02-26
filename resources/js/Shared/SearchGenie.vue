<script setup>
import {nextTick, onBeforeUnmount, onMounted, ref, watch} from 'vue';
import {useSearchStore} from '../stores/SearchStore.js';
import {useUserStore} from "../stores/UserStore.js";
import {Link} from '@inertiajs/inertia-vue3';
import {route} from 'ziggy-js';
import AppDialog from "../components/AppDialog.vue";
import AppTooltip from "../components/AppTooltip.vue";
import SearchFilters from "./_SearchFilters.vue";

const UserStore = useUserStore();
const SearchStore = useSearchStore();

const emit = defineEmits([
    'emitTerm',
    'emitDeck',
    'emitSentence'
]);

const activeIndex = ref(-1);

const tooltip = ref(null);

function handleMouseMove(event) {
    let message = '';

    switch (SearchStore.action) {
        case "search":
            message = "Navigate to this item's page.";
            break;
        case "insert":
            message = "Add this item to the set.";
            break;
        case "pin":
            message = "Pin or Unpin this Deck.";
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
    SearchStore.openSearchGenie(SearchStore.action);

    // todo: focus on search bar on opening search genie
    // nextTick(() => {
    //     searchInput.value?.focus();
    // });
}

const selectModel = (model) => {
    switch (SearchStore.activeModel) {
        case 'terms':
            selectTerm(model);
            break;
        case 'sentences':
            selectSentence(model);
            break;
        case 'decks':
            selectDeck(model);
            break;
    }
};

const selectTerm = (term) => {
    if (SearchStore.action === 'insert') {
        SearchStore.selectModel(term);
    } else {
        window.location.href = `/dictionary/terms/${term.slug}`;
    }
};

const selectSentence = (sentence) => {
    if (SearchStore.action === 'insert') {
        SearchStore.selectModel(sentence);
    } else {
        window.location.href = `/dictionary/sentences/${sentence.id}`;
    }
};

const selectDeck = async (deck) => {
    if (SearchStore.action === 'pin') {
        try {
            const response = await axios.post('/community/decks/' + deck.id + '/pin');

            if (response.data.isPinned) {
                UserStore.user.pinned.decks.push(deck);

            } else {
                UserStore.user.pinned.decks.splice(
                    UserStore.user.pinned.decks.findIndex(item => item.id === deck.id), 1
                )
            }

        } catch (error) {
            console.error('Failed to Pin Deck.', error);
        }

    } else if (SearchStore.action === 'insert') {
        SearchStore.selectModel(deck);

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

                    switch (SearchStore.activeModel) {
                        case 'terms':
                            selectTerm(results[activeIndex.value]);
                            break;
                        case 'sentences':
                            selectSentence(results[activeIndex.value]);
                            break;
                        case 'decks':
                            selectDeck(results[activeIndex.value]);
                            break;
                    }
                }
                break;
        }
    };

    window.addEventListener('keydown', globalListener);
    document.addEventListener('keydown', navigationListener);

    onBeforeUnmount(() => {
        window.removeEventListener('keydown', globalListener);
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
            <div class="sg-tabs">
                <button
                    v-for="tab in SearchStore.tabs"
                    :key="tab.value"
                    :class="{
                        active: SearchStore.activeModel === tab.value,
                        disabled: tab.disabled
                    }"
                    @click="!tab.disabled && setActiveModel(tab.value)"
                >
                    {{ tab.label }}
                </button>
            </div>

            <SearchFilters
                :activeModel="SearchStore.activeModel"
                :filters="SearchStore.filters"
                @updateFilter="({ filter, value }) => SearchStore.updateFilter(filter, value)"
            />

            <div class="sg-results">
                <template v-if="SearchStore.searchResults[SearchStore.activeModel]?.length > 0">
                    <div
                        v-for="(model, index) in SearchStore.searchResults[SearchStore.activeModel]"
                        :key="model.id"
                        :class="['sg-result-item', SearchStore.activeModel, { active: activeIndex === index }]"
                        @mousemove="handleMouseMove($event)"
                        @mouseleave="handleMouseLeave"
                        @mouseover="activeIndex = index"
                        @click="selectModel(model)"
                    >
                        <template v-if="SearchStore.activeModel === 'terms'">
                            <div>{{ model.term }}</div>
                            <div>({{ model.translit }}) {{ model.category }}.</div>
                        </template>
                        <template v-else-if="SearchStore.activeModel === 'sentences'">
                            <div>{{ model.sentence }}</div>
                        </template>
                        <template v-else-if="SearchStore.activeModel === 'decks'">
                            <div>{{ model.name }}</div>
                        </template>
                    </div>
                </template>

                <div v-else class="sg-empty">
                    Nothing yet.
                </div>
            </div>

            <div class="sg-all-results" v-if="SearchStore.searchResults[SearchStore.activeModel]?.length > 0">
                <Link class="sg-all-results" :href="route(`${SearchStore.activeModel}.index`, {
                    search: SearchStore.filters.search,
                    category: SearchStore.filters.category,
                    attribute: SearchStore.filters.attribute,
                    form: SearchStore.filters.form,
                    singular: SearchStore.filters.singular,
                    plural: SearchStore.filters.plural,
                })">
                    See All Results
                </Link>
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
