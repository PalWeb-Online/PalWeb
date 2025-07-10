<script setup>
import Layout from "../../../Shared/Layout.vue";
import DeckItem from "../../../components/DeckItem.vue";
import Paginator from "../../../Shared/Paginator.vue";
import AppTip from "../../../components/AppTip.vue";
import SearchFilters from "../../../Shared/SearchFilters.vue";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
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
    <div id="app-body">
        <div class="window-container">
            <div class="window-header">
                <Link :href="route('decks.index')" class="material-symbols-rounded">home</Link>
                <div class="window-header-url">www.palweb.app/library/decks</div>
                <Link v-if="UserStore.isUser" :href="route('deck-master.build')" class="material-symbols-rounded">add</Link>
                <Link :href="route('decks.random')" class="material-symbols-rounded">keyboard_double_arrow_right</Link>
            </div>
            <div class="window-section-head">
                <h1>deck library</h1>
            </div>

            <div class="window-section-head">
                <h2>Index</h2>
            </div>

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

            <template v-if="totalCount > 0">
                <div class="model-list index-list">
                    <DeckItem v-for="deck in decks.data" :key="deck.id" :model="deck"/>
                </div>
                <Paginator :links="decks.meta.links"/>
            </template>
        </div>
    </div>
</template>
