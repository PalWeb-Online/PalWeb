<script setup>
import Layout from "../../../Shared/Layout.vue";
import DeckItem from "../../../components/DeckItem.vue";
import Paginator from "../../../Shared/Paginator.vue";
import AppTip from "../../../components/AppTip.vue";
import SearchFilters from "../../../Shared/SearchFilters.vue";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import AppButton from "../../../components/AppButton.vue";
import {useUserStore} from "../../../stores/UserStore.js";

const UserStore = useUserStore();

defineOptions({
    layout: Layout
});

defineProps({
    decks: Object,
    totalCount: Number,
    filters: Object,
});

function updateFilter({filter, value}) {
    const searchParams = new URLSearchParams(window.location.search);

    value ? searchParams.set(filter, value) : searchParams.delete(filter);
    searchParams.delete('page');

    const params = Object.fromEntries(searchParams.entries());
    router.get(window.location.pathname, params, {preserveState: true, preserveScroll: true});
}
</script>
<template>
    <Head title="Library: Decks"/>
    <div id="app-head">
        <Link :href="route('decks.index')"><h1>Decks</h1></Link>
    </div>
    <div id="app-body">
        <div class="nav-body">
            <Link v-if="UserStore.isUser" :href="route('deck-master.build')">Create New</Link>
            <div v-else>Index</div>
            <Link :href="route('decks.random')">to Random -></Link>
        </div>
        <div class="search-filters-wrapper">
            <SearchFilters
                :activeModel="'decks'"
                :filters="filters"
                @updateFilter="updateFilter"
            />
            <AppTip>
                <p v-if="totalCount > 0 && !Object.values(filters).every(value => !value)">Displaying {{ totalCount }}
                    Decks
                    matching this query.</p>
                <p v-else-if="totalCount > 0">Displaying all {{ totalCount }} Decks in the Library.</p>
                <p v-else>No Decks matching this query.</p>
            </AppTip>
        </div>

        <template v-if="totalCount > 0">
            <div class="decks-list">
                <DeckItem v-for="deck in decks.data" :key="deck.id" :model="deck"/>
            </div>
            <Paginator :links="decks.meta.links"/>
        </template>
    </div>
</template>
