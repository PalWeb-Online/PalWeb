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
                    :class="['sg-result-item', SearchStore.data.activeModel, { active: activeIndex === index }]"
                    @mousemove="handleMouseMove($event)"
                    @mouseleave="handleMouseLeave"
                    @mouseover="activeIndex = index"
                    @click="selectModel(model)"
                >
                    <template v-if="SearchStore.data.activeModel === 'terms'">
                        <div>{{ model.term }}</div>
                        <div>({{ model.translit }}) {{ model.category }}.</div>
                    </template>
                    <template v-else-if="SearchStore.data.activeModel === 'sentences'">
                        <div>{{ model.sentence }}</div>
                    </template>
                    <template v-else-if="SearchStore.data.activeModel === 'decks'">
                        <div>{{ model.name }}</div>
                    </template>
                </div>
            </template>
            <div v-else-if="!SearchStore.isLoading" class="sg-empty">
                Nothing yet.
            </div>
            <LoadingSpinner v-show="SearchStore.isLoading"/>
        </div>

        <div class="sg-all-results"
             v-if="SearchStore.data.activeModel === 'terms' && SearchStore.data.results.terms?.length > 0">
            <Link class="sg-all-results" @click="() => emit('close')" :href="route('terms.index', {
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
            <Link class="sg-all-results" @click="() => emit('close')" :href="route('sentences.index', {
                    search: SearchStore.data.filters.search,
                    match: SearchStore.data.filters.match,
                    pinned: SearchStore.data.filters.pinned
                })">
                See All Results
            </Link>
        </div>
        <div class="sg-all-results"
             v-if="SearchStore.data.activeModel === 'decks' && SearchStore.data.results.decks?.length > 0">
            <Link class="sg-all-results" @click="() => emit('close')" :href="route('decks.index', {
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
