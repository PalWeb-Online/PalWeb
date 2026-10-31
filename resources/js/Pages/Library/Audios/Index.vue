<script setup>
import {ref} from "vue";
import PronunciationItem from "../../../components/PronunciationItem.vue";
import AppTip from "../../../components/AppTip.vue";
import {useUserStore} from "../../../stores/UserStore.js";
import Paginator from "../../../Shared/Paginator.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import {useResourceIndex} from "../../../composables/resources/useResourceIndex.js";
import PaginationPage from "../../../components/PaginationPage.vue";

const UserStore = useUserStore();

const dialects = ref([]);
const locations = ref([]);

const {
    collection: audios,
    totalCount,
    filters,
    loading,
    currentPage,
    pageNumbers,
    isLoadingPaginator,
    goToPage,
    updateFilter,
} = useResourceIndex({
    routeBase: 'audios',
    defaultFilters: {sort: 'latest', dialect: '', location: '', gender: ''},
    onResponse: (data) => {
        dialects.value = data.dialects ?? [];
        locations.value = data.locations ?? [];
    },
});

const localeKey = (value) => value?.toString().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
</script>

<template>
    <div id="app-body">
        <div class="window-container">
            <div class="window-header">
                <RouterLink :to="{ name: 'audios.index' }" class="material-symbols-rounded">home</RouterLink>
                <div class="window-header-url">www.palweb.app/library/audios</div>
                <RouterLink v-if="UserStore.isUser" :to="{ name: 'sound-booth.index' }" class="material-symbols-rounded">add
                </RouterLink>
            </div>
            <div class="window-section-head"><h1>{{ $t('pages.audios.index.title') }}</h1></div>
            <div class="window-section-head"><h2>{{ $t('pages.common.index') }}</h2></div>

            <div class="search-filters-container">
                <div class="search-filters">
                    <select v-model="filters.dialect" :class="filters.dialect ? 'persisting' : ''"
                            @change="updateFilter({filter: 'dialect', value: filters.dialect})">
                        <option value="">{{ $t('speaker.fields.dialect') }}</option>
                        <option v-for="dialect in dialects" :key="dialect.id" :value="dialect.id">
                            {{ $t(`dialect.${localeKey(dialect.name)}`) }}
                        </option>
                    </select>
                    <select v-model="filters.location" :class="filters.location ? 'persisting' : ''"
                            @change="updateFilter({filter: 'location', value: filters.location})">
                        <option value="">{{ $t('speaker.fields.location') }}</option>
                        <option v-for="location in locations" :key="location.id" :value="location.id">
                            {{ location.name_ar }}
                        </option>
                    </select>
                    <select v-model="filters.gender" :class="filters.gender ? 'persisting' : ''"
                            @change="updateFilter({filter: 'gender', value: filters.gender})">
                        <option value="">{{ $t('speaker.fields.gender') }}</option>
                        <option value="male">{{ $t('speaker.gender.male') }}</option>
                        <option value="female">{{ $t('speaker.gender.female') }}</option>
                    </select>
                    <select v-model="filters.sort" @change="updateFilter({filter: 'sort', value: filters.sort})">
                        <option value="latest">{{ $t('search.filters.sort.latest') }}</option>
                        <option value="fluency">{{ $t('search.filters.sort.fluency') }}</option>
                    </select>
                </div>
            </div>

            <template v-if="loading">
                <AppTip>
                    <p>{{ $t('pages.common.loading', { model: $t('models.audios') }) }}</p>
                </AppTip>
                <LoadingSpinner/>
            </template>
            <template v-else>
                <AppTip>
                    <p v-if="totalCount > 0 && !Object.values(filters).every(value => !value)">
                        {{ $t('pages.common.displaying-results', { count: totalCount, model: $t('models.audios') }) }}
                    </p>
                    <p v-else-if="totalCount > 0">{{ $t('pages.common.displaying-all', { model: $t('models.audios') }) }}</p>
                    <p v-else>{{ $t('pages.common.displaying-none', { model: $t('models.audios') }) }}</p>
                </AppTip>
                <template v-if="totalCount > 0">
                    <PaginationPage :is-loading="isLoadingPaginator">
                        <PronunciationItem v-for="audio in audios.data" :model="audio.pronunciation" :audio="audio"/>
                    </PaginationPage>
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
