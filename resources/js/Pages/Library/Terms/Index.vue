<script setup>
import Layout from "../../../Shared/Layout.vue";
import TermItem from "../../../components/TermItem.vue";
import Paginator from "../../../Shared/Paginator.vue";
import {ref} from "vue";
import {Inertia} from "@inertiajs/inertia";
import AppTip from "../../../components/AppTip.vue";
import SearchFilters from "../../../Shared/_SearchFilters.vue";

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
    Inertia.get(window.location.pathname, params, {preserveState: true, preserveScroll: true});
}
</script>

<template>
    <Head title="Dictionary"/>
    <div id="app-head">
        <h1>Dictionary</h1>
    </div>
    <div id="app-body">
        <!--        todo: put in a collapsible instead -->
        <div class="terms-featured-wrapper">
            <div class="terms-featured-daily">
                <div class="featured-title l" style="text-transform: none">Word of the Day</div>
                <TermItem :model="featuredTerm"/>
            </div>

            <div class="terms-featured-latest">
                <div class="featured-title m" style="text-transform: none">Latest</div>
                <TermItem v-for="term in latestTerms" :model="term"/>
            </div>
        </div>

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

        <AppTip>
            <p v-if="totalCount > 0 && !Object.values(filters).every(value => !value)">Displaying {{ totalCount }} Terms
                matching this query.</p>
            <p v-else-if="totalCount > 0">Displaying all {{ totalCount }} Terms in the Dictionary.</p>
            <p v-else>No Terms matching this query.</p>
        </AppTip>

        <template v-if="totalCount > 0">
            <div class="deck-container">
                <div class="terms-list">
                    <TermItem v-for="term in terms.data" :key="term.id" :model="term"/>
                </div>
            </div>
            <Paginator :links="terms.meta.links"/>
        </template>
    </div>
</template>
