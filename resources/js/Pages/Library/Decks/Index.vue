<script setup>
import Layout from "../../../Shared/Layout.vue";
import DeckItem from "../../../components/DeckItem.vue";
import Paginator from "../../../Shared/Paginator.vue";
import {Inertia} from "@inertiajs/inertia";
import AppTip from "../../../components/AppTip.vue";
import SearchFilters from "../../../Shared/_SearchFilters.vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    decks: Object,
    totalCount: Number,
    filters: Object,
});

function updateFilter({filter, value}) {
    const searchParams = new URLSearchParams(window.location.search);

    value ? searchParams.set(filter, value) : searchParams.delete(filter);
    searchParams.delete('page');

    const params = Object.fromEntries(searchParams.entries());
    Inertia.get(window.location.pathname, params, {preserveState: true, preserveScroll: true});
}
</script>
<template>
    <Head title="Library: Decks"/>
    <div id="app-head">
        <h1>Decks</h1>
    </div>
    <div id="app-body">
        <SearchFilters
            :activeModel="'decks'"
            :filters="filters"
            @updateFilter="updateFilter"
        />

        <AppTip>
            <p v-if="totalCount > 0 && !Object.values(filters).every(value => !value)">Displaying {{ totalCount }} Decks
                matching this query.</p>
            <p v-else-if="totalCount > 0">Displaying all {{ totalCount }} Decks in the Dictionary.</p>
            <p v-else>No Decks matching this query.</p>
        </AppTip>

        <template v-if="totalCount > 0">
            <div class="decks-list">
                <DeckItem v-for="deck in decks.data" :key="deck.id" :model="deck"/>
            </div>
            <Paginator :links="decks.meta.links"/>
        </template>
    </div>
</template>
