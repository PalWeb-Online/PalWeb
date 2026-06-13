<script setup>
import { ref, onMounted, computed } from "vue";
import Layout from "../../../Shared/Layout.vue";
import PronunciationItem from "../../../components/PronunciationItem.vue";
import AppTip from "../../../components/AppTip.vue";
import { route } from "ziggy-js";
import { useUserStore } from "../../../stores/UserStore.js";

const UserStore = useUserStore();
defineOptions({ layout: Layout });

const audios     = ref(null);
const dialects   = ref([]);
const locations  = ref([]);
const totalCount = ref(0);
const loading    = ref(true);
const currentPage = ref(1);
const lastPage    = ref(1);
const filters    = ref({ sort: 'latest', dialect: '', location: '', gender: '' });

async function fetchAudios(params = {}) {
    loading.value = true;
    try {
        const query = new URLSearchParams(params).toString();
        const response = await fetch(`/api/library/audios${query ? '?' + query : ''}`);
        const data = await response.json();
        audios.value     = data.audios;
        dialects.value   = data.dialects;
        locations.value  = data.locations;
        totalCount.value = data.totalCount;
        filters.value    = { ...filters.value, ...data.filters };
        currentPage.value = data.audios.meta.current_page;
        lastPage.value    = data.audios.meta.last_page;
    } catch (error) {
        console.error('Failed to fetch audios:', error);
    } finally {
        loading.value = false;
    }
}

onMounted(() => {
    const params = Object.fromEntries(new URLSearchParams(window.location.search));
    filters.value = { ...filters.value, ...params };
    currentPage.value = parseInt(params.page) || 1;
    fetchAudios(params);
});

function updateFilter(key, value) {
    filters.value[key] = value;
    const params = Object.fromEntries(Object.entries(filters.value).filter(([, v]) => v !== ''));
    const searchParams = new URLSearchParams(params);
    searchParams.delete('page');
    currentPage.value = 1;
    window.history.pushState({}, '', '?' + searchParams.toString());
    fetchAudios(Object.fromEntries(searchParams.entries()));
}

function goToPage(page) {
    if (page < 1 || page > lastPage.value || page === currentPage.value) return;
    const searchParams = new URLSearchParams(window.location.search);
    searchParams.set('page', page);
    window.history.pushState({}, '', '?' + searchParams.toString());
    fetchAudios(Object.fromEntries(searchParams.entries()));
    window.scrollTo(0, 0);
}

const pageNumbers = computed(() => {
    const pages = [];
    const total = lastPage.value;
    const current = currentPage.value;
    if (total <= 7) {
        for (let i = 1; i <= total; i++) pages.push(i);
    } else {
        pages.push(1);
        if (current > 3) pages.push('...');
        for (let i = Math.max(2, current - 1); i <= Math.min(total - 1, current + 1); i++) pages.push(i);
        if (current < total - 2) pages.push('...');
        pages.push(total);
    }
    return pages;
});
</script>

<template>
    <Head title="Library: Audios"/>
    <div id="app-body">
        <div class="window-container">
            <div class="window-header">
                <Link :href="route('audios.index')" class="material-symbols-rounded">home</Link>
                <div class="window-header-url">www.palweb.app/library/audios</div>
                <Link v-if="UserStore.isUser" :href="route('sound-booth.index')" class="material-symbols-rounded">add</Link>
            </div>
            <div class="window-section-head"><h1>audio library</h1></div>
            <div class="window-section-head"><h2>Index</h2></div>

            <div v-if="loading" class="loading-state"><p>Loading...</p></div>

            <template v-else>
                <div class="search-filters-container">
                    <div class="search-filters">
                        <select v-model="filters.dialect" :class="filters.dialect ? 'persisting' : ''" @change="updateFilter('dialect', filters.dialect)">
                            <option value="">Dialect</option>
                            <option v-for="dialect in dialects" :key="dialect.id" :value="dialect.id">{{ dialect.name }}</option>
                        </select>
                        <select v-model="filters.location" :class="filters.location ? 'persisting' : ''" @change="updateFilter('location', filters.location)">
                            <option value="">Location</option>
                            <option v-for="location in locations" :key="location.id" :value="location.id">{{ location.name_ar }}</option>
                        </select>
                        <select v-model="filters.gender" :class="filters.gender ? 'persisting' : ''" @change="updateFilter('gender', filters.gender)">
                            <option value="">Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                        <select v-model="filters.sort" @change="updateFilter('sort', filters.sort)">
                            <option value="latest">by Latest</option>
                            <option value="fluency">by Fluency</option>
                        </select>
                    </div>
                </div>
                <AppTip>
                    <p v-if="totalCount > 0 && !Object.values(filters).every(value => !value)">Displaying {{ totalCount }} Audios matching this query.</p>
                    <p v-else-if="totalCount > 0">Displaying all {{ totalCount }} Audios in the Library.</p>
                    <p v-else>No Audios matching this query.</p>
                </AppTip>
                <template v-if="totalCount > 0">
                    <div class="model-list index-list" style="padding: 3.2rem 1.6rem">
                        <PronunciationItem v-for="audio in audios.data" :model="audio.pronunciation" :audio="audio"/>
                    </div>
                    <div id="paginator">
                        <div class="pagination">

                            <template v-for="page in pageNumbers" :key="page">

                                <a
                                    v-if="page !== '...'"
                                    :class="{ active: page === currentPage }"
                                    style="cursor: pointer"
                                    @click="goToPage(page)"
                                >
                                    {{ page }}
                                </a>

                                <div v-else class="disabled">...</div>

                            </template>

                        </div>
                    </div>

                </template>
            </template>
        </div>
    </div>
</template>