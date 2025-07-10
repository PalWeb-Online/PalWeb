<script setup>
import Layout from "../../../Shared/Layout.vue";
import PronunciationItem from "../../../components/PronunciationItem.vue";
import Paginator from "../../../Shared/Paginator.vue";
import AppTip from "../../../components/AppTip.vue";
import {ref, watch} from "vue";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {useUserStore} from "../../../stores/UserStore.js";

const UserStore = useUserStore();

defineOptions({
    layout: Layout
});

const props = defineProps({
    audios: Object,
    dialects: Array,
    locations: Array,
    totalCount: Number,
    filters: Object,
});

const filters = ref({
    dialect: props.filters.dialect || '',
    location: props.filters.location || '',
    gender: props.filters.gender || '',
    sort: props.filters.sort || '',
});

let previousFilters = {...filters.value};

watch(
    filters,
    (newFilters) => {
        for (const key in newFilters) {
            if (newFilters[key] !== previousFilters[key]) {
                updateFilter({filter: key, value: newFilters[key]});
                previousFilters[key] = newFilters[key];
            }
        }
    },
    {deep: true}
);

function updateFilter({filter, value}) {
    console.log({filter, value});
    const searchParams = new URLSearchParams(window.location.search);

    value ? searchParams.set(filter, value) : searchParams.delete(filter);
    searchParams.delete('page');

    const params = Object.fromEntries(searchParams.entries());
    router.get(window.location.pathname, params, {preserveState: true, preserveScroll: true});
}
</script>
<template>
    <Head title="Library: Audios"/>
    <div id="app-body">
        <div class="window-container">
            <div class="window-header">
                <Link :href="route('audios.index')" class="material-symbols-rounded">home</Link>
                <div class="window-header-url">www.palweb.app/library/audios</div>
                <Link v-if="UserStore.isUser" :href="route('record-wizard.index')" class="material-symbols-rounded">add</Link>
            </div>
            <div class="window-section-head">
                <h1>audio library</h1>
            </div>

            <div class="window-section-head">
                <h2>Index</h2>
            </div>
            <div class="search-filters-container">
                <div class="search-filters">
                    <select v-model="filters.dialect" :class="filters.dialect ? 'persisting' : ''">
                        <option value="">Dialect</option>
                        <option v-for="dialect in dialects" :value="dialect.id">{{ dialect.name }}</option>
                    </select>

                    <select v-model="filters.location" :class="filters.location ? 'persisting' : ''">
                        <option value="">Location</option>
                        <option v-for="location in locations" :value="location.id">{{ location.name_ar }}</option>
                    </select>

                    <select v-model="filters.gender" :class="filters.gender ? 'persisting' : ''">
                        <option value="">Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>

                    <select v-model="filters.sort">
                        <option value="latest">by Latest</option>
                        <option value="fluency">by Fluency</option>
                    </select>
                </div>
            </div>

            <AppTip>
                <p v-if="totalCount > 0 && !Object.values(filters).every(value => !value)">Displaying {{ totalCount }}
                    Audios
                    matching this query.</p>
                <p v-else-if="totalCount > 0">Displaying all {{ totalCount }} Audios in the Library.</p>
                <p v-else>No Audios matching this query.</p>
            </AppTip>

            <template v-if="totalCount > 0">
                <div class="model-list index-list" style="padding: 3.2rem 1.6rem">
                    <PronunciationItem v-for="audio in audios.data" :model="audio.pronunciation" :audio="audio"/>
                </div>
                <Paginator :links="audios.meta.links"/>
            </template>
        </div>
    </div>
</template>
