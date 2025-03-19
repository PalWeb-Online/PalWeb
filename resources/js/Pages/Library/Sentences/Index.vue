<script setup>
import Layout from "../../../Shared/Layout.vue";
import SentenceContainer from "../../../components/SentenceContainer.vue";
import Paginator from "../../../Shared/Paginator.vue";
import AppTip from "../../../components/AppTip.vue";
import SearchFilters from "../../../Shared/SearchFilters.vue";
import {router} from "@inertiajs/vue3";

defineOptions({
    layout: Layout
});

defineProps({
    sentences: Object,
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
    <Head title="Library: Sentences"/>
    <div id="app-head">
        <h1>Sentences</h1>
    </div>
    <div id="app-body">
        <SearchFilters
            :activeModel="'sentences'"
            :filters="filters"
            @updateFilter="updateFilter"
        />

        <AppTip>
            <p v-if="totalCount > 0 && !Object.values(filters).every(value => !value)">Displaying {{ totalCount }}
                Sentences
                matching this query.</p>
            <p v-else-if="totalCount > 0">Displaying all {{ totalCount }} Sentences in the Library.</p>
            <p v-else>No Sentences matching this query.</p>
        </AppTip>

        <template v-if="totalCount > 0">
            <div class="deck-container">
                <div class="sentences-list">
                    <SentenceContainer v-for="sentence in sentences.data" :key="sentence.id" :model="sentence"/>
                </div>
            </div>
            <Paginator :links="sentences.meta.links"/>
        </template>
    </div>
</template>
