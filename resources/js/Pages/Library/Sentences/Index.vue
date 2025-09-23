<script setup>
import Layout from "../../../Shared/Layout.vue";
import SentenceItem from "../../../components/SentenceItem.vue";
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
    <Head title="Library: Corpus"/>
    <div id="app-body">
        <div class="window-container">
            <div class="window-header">
                <Link :href="route('sentences.index')" class="material-symbols-rounded">home</Link>
                <div class="window-header-url">www.palweb.app/library/sentences</div>
                <Link v-if="UserStore.isAdmin" :href="route('speech-maker.sentence')" class="material-symbols-rounded">add</Link>
                <Link :href="route('sentences.random')" class="material-symbols-rounded">keyboard_double_arrow_right</Link>
            </div>
            <div class="window-section-head">
                <h1>corpus</h1>
            </div>

            <div class="window-section-head">
                <h2>Index</h2>
            </div>
            <SearchFilters
                :activeModel="'sentences'"
                :filters="filters"
                @updateFilter="updateFilter"
            />
            <AppTip>
                <p v-if="totalCount > 0 && !Object.values(filters).every(value => !value)">Displaying {{
                        totalCount
                    }}
                    Sentences matching this query.</p>
                <p v-else-if="totalCount > 0">Displaying all {{ totalCount }} Sentences in the Library.</p>
                <p v-else>No Sentences matching this query.</p>
            </AppTip>
            <template v-if="totalCount > 0">
                <div class="model-list index-list">
                    <SentenceItem v-for="sentence in sentences.data" :key="sentence.id" :model="sentence"/>
                </div>
                <Paginator :links="sentences.meta.links"/>
            </template>
        </div>
    </div>
</template>
