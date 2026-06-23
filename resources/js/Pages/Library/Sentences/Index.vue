<script setup>
import {ref, onMounted} from "vue";
import Layout from "../../../Shared/Layout.vue";
import SentenceItem from "../../../components/SentenceItem.vue";
import AppTip from "../../../components/AppTip.vue";
import SearchFilters from "../../../Shared/SearchFilters.vue";
import {route} from "ziggy-js";
import {useUserStore} from "../../../stores/UserStore.js";
import {usePaginator} from "../../../composables/usePaginator.js";
import Paginator from "../../../Shared/Paginator.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";

const UserStore = useUserStore();
defineOptions({layout: Layout});

const sentences = ref(null);
const totalCount = ref(0);
const filters = ref({sort: 'latest'});
const loading = ref(true);

const {currentPage, pageNumbers, goToPage, updatePagination} = usePaginator(fetchSentences);

async function fetchSentences(params = {}) {
    loading.value = true;
    try {
        const response = await fetch(route('api.sentences.index', params));
        const data = await response.json();
        sentences.value = data.sentences;
        totalCount.value = data.totalCount;
        filters.value = data.filters;
        updatePagination(data.sentences.meta);
    } catch (error) {
        console.error('Failed to fetch sentences:', error);
    } finally {
        loading.value = false;
    }
}

onMounted(() => {
    const params = Object.fromEntries(new URLSearchParams(window.location.search));
    currentPage.value = parseInt(params.page) || 1;
    fetchSentences(params);
});

function updateFilter({filter, value}) {
    const searchParams = new URLSearchParams(window.location.search);
    value ? searchParams.set(filter, value) : searchParams.delete(filter);
    searchParams.delete('page');
    currentPage.value = 1;
    window.history.pushState({}, '', '?' + searchParams.toString());
    fetchSentences(Object.fromEntries(searchParams.entries()));
}
</script>

<template>
    <Head title="Library: Corpus"/>
    <div id="app-body">
        <div class="window-container">
            <div class="window-header">
                <Link :href="route('sentences.index')" class="material-symbols-rounded">home</Link>
                <div class="window-header-url">www.palweb.app/library/sentences</div>
                <Link v-if="UserStore.isAdmin" :href="route('speech-maker.sentence')" class="material-symbols-rounded">
                    add
                </Link>
                <Link :href="route('sentences.random')" class="material-symbols-rounded">keyboard_double_arrow_right
                </Link>
            </div>
            <div class="window-section-head"><h1>corpus</h1></div>
            <div class="window-section-head"><h2>Index</h2></div>

            <SearchFilters :activeModel="'sentences'" :filters="filters" @updateFilter="updateFilter"/>

            <template v-if="loading">
                <AppTip>
                    <p>Loading...</p>
                </AppTip>
                <LoadingSpinner/>
            </template>
            <template v-else>
                <AppTip>
                    <p v-if="totalCount > 0 && !Object.values(filters).every(value => !value)">
                        Displaying {{ totalCount }} Sentences matching this query.
                    </p>
                    <p v-else-if="totalCount > 0">Displaying all {{ totalCount }} Sentences in the Library.</p>
                    <p v-else>No Sentences matching this query.</p>
                </AppTip>
                <template v-if="totalCount > 0">
                    <div class="model-list index-list">
                        <SentenceItem v-for="sentence in sentences.data" :key="sentence.id" :model="sentence"/>
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
