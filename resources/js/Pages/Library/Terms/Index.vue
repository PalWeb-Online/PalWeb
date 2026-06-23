<script setup>
import {computed, ref, onMounted} from "vue";
import Layout from "../../../Shared/Layout.vue";
import TermItem from "../../../components/TermItem.vue";
import AppTip from "../../../components/AppTip.vue";
import SearchFilters from "../../../Shared/SearchFilters.vue";
import {useUserStore} from "../../../stores/UserStore.js";
import {route} from "ziggy-js";
import TermFeatured from "../../../components/TermFeatured.vue";
import {useNavigationStore} from "../../../stores/NavigationStore.js";
import {usePaginator} from "../../../composables/usePaginator.js";
import Paginator from "../../../Shared/Paginator.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";

const UserStore = useUserStore();
const NavigationStore = useNavigationStore();

defineOptions({layout: Layout});

const props = defineProps({
    featuredTerm: Object,
})

const terms = ref(null);
const totalCount = ref(0);
const filters = ref({sort: 'alphabetical'});
const loading = ref(true);

const letters = [
    'ب', 'ت', 'ث', 'ج', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف', 'ق',
    'ك', 'ل', 'م', 'ن', 'ه', 'و', 'ي', 'ء'
];
const selectedLetter = ref(null);

const {currentPage, pageNumbers, goToPage, updatePagination} = usePaginator(fetchTerms);

async function fetchTerms(params = {}) {
    loading.value = true;
    try {
        const response = await fetch(route('api.terms.index', params));
        const data = await response.json();
        terms.value = data.terms;
        totalCount.value = data.totalCount;
        filters.value = data.filters;
        updatePagination(data.terms.meta);
    } catch (error) {
        console.error('Failed to fetch terms:', error);
    } finally {
        loading.value = false;
    }
}

onMounted(() => {
    const params = Object.fromEntries(new URLSearchParams(window.location.search));
    selectedLetter.value = params.letter || null;
    currentPage.value = parseInt(params.page) || 1;
    fetchTerms(params);
});

function updateFilter({filter, value}) {
    const searchParams = new URLSearchParams(window.location.search);
    if (filter === 'letter') {
        const newLetter = selectedLetter.value === value ? null : value;
        newLetter ? searchParams.set(filter, newLetter) : searchParams.delete(filter);
        selectedLetter.value = newLetter;
    } else {
        value ? searchParams.set(filter, value) : searchParams.delete(filter);
    }
    searchParams.delete('page');
    currentPage.value = 1;
    window.history.pushState({}, '', '?' + searchParams.toString());
    fetchTerms(Object.fromEntries(searchParams.entries()));
}

const sortingMessage = computed(() => {
    if (filters.value.sort === 'latest') return 'sorted by most recent first';
    if (filters.value.sort === 'pinned') return 'in the order they were Pinned';
    return 'sorted alphabetically by root';
});
</script>

<template>
    <Head title="Dictionary"/>
    <div id="app-body">
        <div class="window-container">
            <div class="window-header">
                <Link :href="route('terms.index')" class="material-symbols-rounded">home</Link>
                <div class="window-header-url">www.palweb.app/library/terms</div>
                <Link v-if="UserStore.isAdmin" :href="route('word-logger.term')" class="material-symbols-rounded">add
                </Link>
                <Link :href="route('terms.random')" class="material-symbols-rounded">keyboard_double_arrow_right</Link>
            </div>
            <div class="window-section-head"><h1>dictionary</h1></div>

            <TermFeatured :model="featuredTerm"/>
            <div class="window-section-head"><h2>index</h2></div>

            <div class="letters-array">
                <button
                    v-for="letter in letters"
                    :key="letter"
                    :class="{ 'active': selectedLetter === letter }"
                    @click="updateFilter({ filter: 'letter', value: letter })"
                >{{ letter }}
                </button>
            </div>
            <SearchFilters activeModel="terms" :filters="filters" @updateFilter="updateFilter"/>

            <template v-if="loading">
                <AppTip>
                    <p>Loading...</p>
                </AppTip>
                <LoadingSpinner/>
            </template>
            <template v-else>
                <AppTip>
                    <p v-if="totalCount > 0 && !Object.values(filters).every(value => !value)">
                        Displaying {{ totalCount }} Terms matching this query, {{ sortingMessage }}.
                    </p>
                    <p v-else-if="totalCount > 0">Displaying all {{ totalCount }} Terms in the Dictionary.</p>
                    <p v-else>
                        No Terms matching this query. Is a term missing from the Dictionary?
                        <a @click="NavigationStore.showSendFeedback = true" style="cursor: pointer">Click here to let us
                            know!</a>
                    </p>
                </AppTip>
                <template v-if="totalCount > 0">
                    <div class="model-list index-list">
                        <TermItem v-for="term in terms.data" :key="term.id" :model="term"/>
                    </div>
                    <Paginator
                        :pageNumbers="pageNumbers"
                        :currentPage="currentPage"
                        :goToPage="goToPage"
                    />
                </template>
            </template>
        </div>
    </div>
</template>

<style scoped lang="scss">
.letters-array {
    justify-items: center;
    padding: 3.2rem;
    background: var(--color-pastel-light);
    width: 100%;
    display: flex;
    flex-flow: row wrap;
    justify-content: center;
    gap: 1.6rem;
    direction: rtl;

    button {
        width: 4.8rem;
        height: 4.8rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: var(--mono-font);
        font-size: 2.4rem;
        font-weight: 700;
        user-select: none;
        color: var(--color-accent-medium);
        background: white;

        &:hover {
            color: var(--color-dark-primary)
        }

        &.active {
            color: white;
            background: var(--color-dark-primary);
        }
    }
}
</style>
