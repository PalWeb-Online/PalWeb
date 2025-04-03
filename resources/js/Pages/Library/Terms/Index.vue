<script setup>
import {ref} from "vue";
import Layout from "../../../Shared/Layout.vue";
import TermItem from "../../../components/TermItem.vue";
import Paginator from "../../../Shared/Paginator.vue";
import AppTip from "../../../components/AppTip.vue";
import SearchFilters from "../../../Shared/SearchFilters.vue";
import {router} from "@inertiajs/vue3";
import {useUserStore} from "../../../stores/UserStore.js";
import {route} from "ziggy-js";
import TermFeatured from "../../../components/TermFeatured.vue";
import {useNavigationStore} from "../../../stores/NavigationStore.js";

const UserStore = useUserStore();
const NavigationStore = useNavigationStore();

defineOptions({
    layout: Layout
});

const props = defineProps({
    terms: Object,
    totalCount: Number,
    latestTerms: Object,
    featuredTerm: Object,
    filters: Object,
});

const letters = [
    'ب', 'ت', 'ث', 'ج', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف', 'ق',
    'ك', 'ل', 'م', 'ن', 'ه', 'و', 'ي', 'ء'
];

const selectedLetter = ref(props.filters.letter || null);

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

    const params = Object.fromEntries(searchParams.entries());
    router.get(window.location.pathname, params, {preserveState: true, preserveScroll: true});
}
</script>

<template>
    <Head title="Dictionary"/>
    <div id="app-head">
        <Link :href="route('terms.index')"><h1>Dictionary</h1></Link>
    </div>
    <div id="app-body">
        <div class="nav-body">
            <Link v-if="!UserStore.isAdmin" :href="route('terms.create')">Create New</Link>
            <div v-else>Index</div>
            <Link :href="route('terms.random')">to Random -></Link>
        </div>
        <div class="terms-featured-wrapper" v-if="Object.values(filters).every(value => !value)">
            <TermFeatured :model="featuredTerm"/>

            <div class="terms-featured-latest">
                <div class="featured-title m" style="text-transform: none">Latest</div>
                <TermItem v-for="term in latestTerms" :model="term"/>
            </div>
        </div>

        <div class="search-filters-wrapper">
            <div class="letters-array">
                <button
                    v-for="letter in letters"
                    :key="letter"
                    :class="{ 'active': selectedLetter === letter }"
                    @click="updateFilter({ filter: 'letter', value: letter })"
                >
                    {{ letter }}
                </button>
            </div>
            <SearchFilters
                :activeModel="'terms'"
                :filters="filters"
                @updateFilter="updateFilter"
            />
        </div>

        <div class="popup-window index-container">
            <div class="window-head">
                terms
            </div>
            <AppTip>
                <p v-if="totalCount > 0 && !Object.values(filters).every(value => !value)">Displaying {{ totalCount }}
                    Terms matching this query.</p>
                <p v-else-if="totalCount > 0">Displaying all {{ totalCount }} Terms in the Dictionary.</p>
                <p v-else>No Terms matching this query. Is a term missing from the Dictionary?
                    <a @click="NavigationStore.showSendFeedback = true" style="cursor: pointer">Click here to let us
                        know!</a>
                </p>
            </AppTip>
            <template v-if="totalCount > 0">
                <div class="terms-list">
                    <TermItem v-for="term in terms.data" :key="term.id" :model="term"/>
                </div>
                <Paginator :links="terms.meta.links"/>
            </template>
        </div>
    </div>
</template>
