<script setup>
import { computed, ref, onMounted } from "vue";
import Layout from "../../../Shared/Layout.vue";
import TermItem from "../../../components/TermItem.vue";
import AppTip from "../../../components/AppTip.vue";
import SearchFilters from "../../../Shared/SearchFilters.vue";
import { useUserStore } from "../../../stores/UserStore.js";
import { route } from "ziggy-js";
import TermFeatured from "../../../components/TermFeatured.vue";
import { useNavigationStore } from "../../../stores/NavigationStore.js";

const UserStore = useUserStore();
const NavigationStore = useNavigationStore();

defineOptions({ layout: Layout });

const terms        = ref(null);
const totalCount   = ref(0);
const featuredTerm = ref(null);
const filters      = ref({ sort: 'alphabetical' });
const loading      = ref(true);
const currentPage  = ref(1);
const lastPage     = ref(1);

const letters = [
    'ب', 'ت', 'ث', 'ج', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف', 'ق',
    'ك', 'ل', 'م', 'ن', 'ه', 'و', 'ي', 'ء'
];

const selectedLetter = ref(null);

async function fetchTerms(params = {}) {
    loading.value = true;
    try {
        const query = new URLSearchParams(params).toString();
        const response = await fetch(`/api/library/terms${query ? '?' + query : ''}`);
        const data = await response.json();

        terms.value        = data.terms;
        totalCount.value   = data.totalCount;
        featuredTerm.value = data.featuredTerm;
        filters.value      = data.filters;
        currentPage.value  = data.terms.meta.current_page;
        lastPage.value     = data.terms.meta.last_page;
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

function updateFilter({ filter, value }) {
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

    const params = Object.fromEntries(searchParams.entries());
    window.history.pushState({}, '', '?' + searchParams.toString());
    fetchTerms(params);
}

function goToPage(page) {
    if (page < 1 || page > lastPage.value) return;
    currentPage.value = page;

    const searchParams = new URLSearchParams(window.location.search);
    searchParams.set('page', page);
    window.history.pushState({}, '', '?' + searchParams.toString());

    const params = Object.fromEntries(searchParams.entries());
    fetchTerms(params);
    window.scrollTo(0, 0);
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
                <Link v-if="UserStore.isAdmin" :href="route('word-logger.term')" class="material-symbols-rounded">add</Link>
                <Link :href="route('terms.random')" class="material-symbols-rounded">keyboard_double_arrow_right</Link>
            </div>
            <div class="window-section-head">
                <h1>dictionary</h1>
            </div>

            <div v-if="loading" class="loading-state">
                <p>Loading...</p>
            </div>

            <template v-else>
                <TermFeatured :model="featuredTerm"/>
                <div class="window-section-head">
                    <h2>index</h2>
                </div>
                <div class="letters-array">
                    <button
                        v-for="letter in letters"
                        :key="letter"
                        :class="{ 'active': selectedLetter === letter }"
                        @click="updateFilter({ filter: 'letter', value: letter })"
                    >{{ letter }}</button>
                </div>
                <SearchFilters
                    activeModel="terms"
                    :filters="filters"
                    @updateFilter="updateFilter"
                />
                <AppTip>
                    <p v-if="totalCount > 0 && !Object.values(filters).every(value => !value)">
                        Displaying {{ totalCount }} Terms matching this query, {{ sortingMessage }}.
                    </p>
                    <p v-else-if="totalCount > 0">Displaying all {{ totalCount }} Terms in the Dictionary.</p>
                    <p v-else>
                        No Terms matching this query. Is a term missing from the Dictionary?
                        <a @click="NavigationStore.showSendFeedback = true" style="cursor: pointer">
                            Click here to let us know!
                        </a>
                    </p>
                </AppTip>
                <template v-if="totalCount > 0">
                    <div class="model-list index-list">
                        <TermItem v-for="term in terms.data" :key="term.id" :model="term"/>
                    </div>

                    <!-- Pagination manuelle -->
                    <div class="pagination" style="display: flex; gap: 8px; justify-content: center; padding: 2rem;">
                        <button
                            @click="goToPage(currentPage - 1)"
                            :disabled="currentPage <= 1"
                            class="pagination-btn"
                        >← Prev</button>

                        <span style="padding: 0.5rem 1rem;">
                            Page {{ currentPage }} / {{ lastPage }}
                        </span>

                        <button
                            @click="goToPage(currentPage + 1)"
                            :disabled="currentPage >= lastPage"
                            class="pagination-btn"
                        >Next →</button>
                    </div>
                </template>
            </template>
        </div>
    </div>
</template>