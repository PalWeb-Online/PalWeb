<script setup>
import { ref, onMounted } from "vue";
import Layout from "../../../Shared/Layout.vue";
import DeckItem from "../../../components/DeckItem.vue";
import Paginator from "../../../Shared/Paginator.vue";
import AppTip from "../../../components/AppTip.vue";
import SearchFilters from "../../../Shared/SearchFilters.vue";
import { route } from "ziggy-js";
import { useUserStore } from "../../../stores/UserStore.js";

const UserStore = useUserStore();
defineOptions({ layout: Layout });

const decks      = ref(null);
const totalCount = ref(0);
const filters    = ref({ sort: 'latest' });
const loading    = ref(true);

async function fetchDecks(params = {}) {
    loading.value = true;
    try {
        const query = new URLSearchParams(params).toString();
        const response = await fetch(`/api/library/decks${query ? '?' + query : ''}`);
        const data = await response.json();
        decks.value      = data.decks;
        totalCount.value = data.totalCount;
        filters.value    = data.filters;
    } catch (error) {
        console.error('Failed to fetch decks:', error);
    } finally {
        loading.value = false;
    }
}

onMounted(() => {
    const params = Object.fromEntries(new URLSearchParams(window.location.search));
    fetchDecks(params);
});

function updateFilter({ filter, value }) {
    const searchParams = new URLSearchParams(window.location.search);
    value ? searchParams.set(filter, value) : searchParams.delete(filter);
    searchParams.delete('page');
    const params = Object.fromEntries(searchParams.entries());
    window.history.pushState({}, '', '?' + searchParams.toString());
    fetchDecks(params);
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

            <div v-if="loading" class="loading-state">
                <p>Loading...</p>
            </div>

            <template v-else>
                <SearchFilters
                    :activeModel="'decks'"
                    :filters="filters"
                    @updateFilter="updateFilter"
                />
                <AppTip>
                    <p v-if="totalCount > 0 && !Object.values(filters).every(value => !value)">
                        Displaying {{ totalCount }} Decks matching this query.
                    </p>
                    <p v-else-if="totalCount > 0">Displaying all {{ totalCount }} Decks in the Library.</p>
                    <p v-else>No Decks matching this query.</p>
                </AppTip>
                <template v-if="totalCount > 0">
                    <div class="model-list index-list">
                        <DeckItem v-for="deck in decks.data" :key="deck.id" :model="deck"/>
                    </div>
                    <Paginator :links="decks.meta.links"/>
                </template>
            </template>
        </div>
    </div>
</template>