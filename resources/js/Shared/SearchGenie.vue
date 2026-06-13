<script setup>
import {onBeforeUnmount, onMounted, ref, watch} from 'vue';
import {useSearchStore} from '../stores/SearchStore.js';
import {router} from '@inertiajs/vue3';
import {route} from 'ziggy-js';
import AppTooltip from "../components/AppTooltip.vue";
import SearchFilters from "./SearchFilters.vue";
import PopupWindow from "../components/Modals/PopupWindow.vue";
import LoadingSpinner from "./LoadingSpinner.vue";

const SearchStore = useSearchStore();
const emit = defineEmits(['close']);

const activeIndex = ref(-1);

const tooltip = ref(null);

const modifierKeyPrefix = navigator.platform.startsWith("Mac") ? "Cmd" : "Ctrl";

function handleMouseMove(event) {
    if (!tooltip.value) return;

    let message = '';

    switch (SearchStore.data.action) {
        case "search":
            message = "Navigate to this item's page.";
            break;
        case "insert":
            message = "Add this item to the set.";
            break;
    }

    tooltip.value.showTooltip(message, event);
}

function handleMouseLeave() {
    if (!tooltip.value) return;
    tooltip.value.hideTooltip();
}

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
    SearchStore.data.activeModel = model;

    if (model === 'terms') {
        SearchStore.data.filters.sort = 'alphabetical';

    } else {
        SearchStore.data.filters.sort = 'latest';
    }
};

const selectModel = (model) => {
    const routes = {
        terms: {route: 'terms.show', idField: 'slug'},
        sentences: {route: 'sentences.show', idField: 'id'},
        decks: {route: 'decks.show', idField: 'id'},
    };

    if (SearchStore.data.action === 'insert') {
        SearchStore.selectModel(model);

    } else if (routes[SearchStore.data.activeModel]) {
        const {route: targetRoute, idField} = routes[SearchStore.data.activeModel];
        const param = model[idField];

        router.get(route(targetRoute, param), {}, {
            onSuccess: () => {
                emit('close');
            }
        });
    }
};

const filtersRef = ref(null);

onMounted(() => {
    filtersRef.value?.focusInput();

    const navigationListener = (event) => {
        if (!SearchStore.data.isOpen) return;

        const results = SearchStore.data.results[SearchStore.data.activeModel] || [];
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
                    selectModel(results[activeIndex.value]);
                }
                break;
        }
    };

    document.addEventListener('keydown', navigationListener);

    onBeforeUnmount(() => {
        SearchStore.resetSearchGenie();
        document.removeEventListener('keydown', navigationListener);
    });
});

watch(() => SearchStore.data.results, () => {
    activeIndex.value = -1;
});
</script>

<template>
    <div class="window-container modal-container">
        <div class="window-section-head">
            <h1>{{
                    SearchStore.data.action === 'insert' ? 'Insert ' + SearchStore.data.activeModel : 'Search Genie'
                }}</h1>
            <PopupWindow title="Search Genie">
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
            </PopupWindow>
        </div>

        <div class="sg-tabs">
            <button
                v-for="[model, tab] in Object.entries(SearchStore.tabs)"
                :class="{
                        active: SearchStore.data.activeModel === model,
                        disabled: tab.disabled
                    }"
                @click="!tab.disabled && setActiveModel(model)"
            >
                {{ tab.label }}
            </button>
        </div>

        <SearchFilters
            ref="filtersRef"
            :activeModel="SearchStore.data.activeModel"
            :filters="SearchStore.data.filters"
            @updateFilter="({ filter, value }) => SearchStore.search(filter, value)"
        />

        <div class="sg-results">
            <template
                v-if="!SearchStore.isLoading && SearchStore.data.results[SearchStore.data.activeModel]?.length > 0">
                <div
                    v-for="(model, index) in SearchStore.data.results[SearchStore.data.activeModel]"
                    :key="model.id"
                    :class="['sg-result-item', SearchStore.data.activeModel, { active: activeIndex === index, locked: SearchStore.data.activeModel === 'decks' && !model.unlocked }]"
                    @mousemove="handleMouseMove($event)"
                    @mouseleave="handleMouseLeave"
                    @mouseover="activeIndex = index"
                    @click="selectModel(model)"
                >
                    <template v-if="SearchStore.data.activeModel === 'terms'">
                        <div>{{ model.term }}</div>
                        <div>({{ model.translit }}) {{ model.category }}.</div>
                    </template>
                    <div v-else-if="SearchStore.data.activeModel === 'sentences'">{{ model.sentence }}</div>
                    <div v-else-if="SearchStore.data.activeModel === 'decks'">{{ model.name }}</div>
                </div>
            </template>
            <div v-else-if="!SearchStore.isLoading" class="sg-empty">
                Nothing yet.
            </div>
            <LoadingSpinner v-show="SearchStore.isLoading"/>
        </div>

        <div class="sg-all-results"
             v-if="SearchStore.data.activeModel === 'terms' && SearchStore.data.results.terms?.length > 0">
            <Link class="sg-all-results" :href="route('terms.index', {
                    search: SearchStore.data.filters.search,
                    match: SearchStore.data.filters.match,
                    pinned: SearchStore.data.filters.pinned,
                    category: SearchStore.data.filters.category,
                    attribute: SearchStore.data.filters.attribute,
                    form: SearchStore.data.filters.form,
                    singular: SearchStore.data.filters.singular,
                    plural: SearchStore.data.filters.plural
                })">
                See All Results
            </Link>
        </div>
        <div class="sg-all-results"
             v-if="SearchStore.data.activeModel === 'sentences' && SearchStore.data.results.sentences?.length > 0">
            <Link class="sg-all-results" :href="route('sentences.index', {
                    search: SearchStore.data.filters.search,
                    match: SearchStore.data.filters.match,
                    pinned: SearchStore.data.filters.pinned
                })">
                See All Results
            </Link>
        </div>
        <div class="sg-all-results"
             v-if="SearchStore.data.activeModel === 'decks' && SearchStore.data.results.decks?.length > 0">
            <Link class="sg-all-results" :href="route('decks.index', {
                    search: SearchStore.data.filters.search,
                    match: SearchStore.data.filters.match,
                    pinned: SearchStore.data.filters.pinned
                })">
                See All Results
            </Link>
        </div>

        <div class="sg-footer">
            <div><b>Enter</b> selects</div>
            <div><b>Up/Down</b> navigates</div>
            <div v-if="SearchStore.data.action === 'search'">
                <b>{{ modifierKeyPrefix }}+K</b> toggles Search Genie
            </div>
            <div v-else><b>Esc</b> exits</div>
        </div>
    </div>

    <AppTooltip ref="tooltip"/>
</template>

<style scoped lang="scss">
.sg-tabs {
    min-height: 3.6rem;
    display: flex;
    gap: 0.8rem;
    margin: 1.2rem;

    button {
        flex: 1;
        padding: 0.8rem 1.6rem;
        border: none;
        color: var(--color-dark-primary);
        background: var(--color-pastel-light);
        border-radius: 0.6rem;
        font-size: 1.4rem;
        font-family: var(--mono-font);
        text-transform: capitalize;
        cursor: pointer;

        &.active {
            color: white;
            background: var(--color-medium-primary);
            font-weight: 700;
        }

        &.disabled {
            cursor: not-allowed;
            filter: grayscale(100%) opacity(33%);
        }
    }
}

.sg-results {
    display: grid;
    gap: 0.4rem;
    overflow: auto;
    border-block-start: 0.1rem solid var(--color-pastel-dark);
    padding: 1.2rem;
    scrollbar-width: thin;

    & > * {
        padding-block: 1.2rem;
    }

    .loading-spinner svg {
        width: 4.8rem;
    }

    .sg-result-item {
        color: var(--color-dark-primary);
        background: var(--color-pastel-light);
        padding-inline: 1.6rem;
        border-radius: 0.4rem;
        cursor: pointer;

        &.active {
            color: white;
            background: var(--color-medium-primary);
        }
    }

    .sg-empty {
        color: var(--color-pastel-dark);
        text-align: center;
        font-size: 1.4rem;
        font-family: var(--mono-font);
    }
}

.sg-all-results {
    display: grid;
    padding: 0.8rem 1.2rem;

    a {
        padding: 1.2rem 1.6rem;
        border-radius: 0.4rem;
        font-size: 1.2rem;
        font-family: var(--mono-font);
        text-align: center;
        color: var(--color-dark-primary);
        background: white;
        border: 0.1rem solid var(--color-medium-primary);

        &:hover {
            border: 0.1rem solid var(--color-accent-medium);
        }
    }
}

.sg-footer {
    display: flex;
    gap: 0.4rem 1.6rem;
    flex-flow: row wrap;
    color: var(--color-dark-primary);
    background: var(--color-accent-light);
    padding: 0.6rem 1.2rem;
    border-block-start: 0.1rem solid var(--color-accent-medium);
    font-size: 1.2rem;
    font-family: var(--mono-font);
}

.sg-result-item {
    line-height: 1.5;

    &.terms {
        display: flex;
        flex-flow: row-reverse wrap;
        direction: ltr;
        justify-content: space-between;
        align-items: center;
        gap: 0.8rem;

        & > div:nth-child(1) {
            font-size: 1.6rem;
            font-weight: 700;
            font-family: var(--ar-body-font);
        }

        & > div:nth-child(2) {
            font-size: 1.2rem;
            font-weight: 400;
            font-family: var(--mono-font);
        }
    }

    &.sentences {
        display: flex;
        flex-flow: row-reverse wrap;
        direction: ltr;
        font-size: 1.6rem;
        font-family: var(--ar-body-font);
    }

    &.decks {
        &.locked {
            opacity: 0.33;
            user-select: none;
            pointer-events: none;
        }
    }
}
</style>
