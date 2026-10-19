<script setup>
import Layout from "../../../Shared/Layout.vue";
import SentenceItem from "../../../components/SentenceItem.vue";
import AppTip from "../../../components/AppTip.vue";
import SearchFilters from "../../../Shared/SearchFilters.vue";
import {useUserStore} from "../../../stores/UserStore.js";
import Paginator from "../../../Shared/Paginator.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import {useResourceIndex} from "../../../composables/resources/useResourceIndex.js";

const UserStore = useUserStore();
defineOptions({layout: Layout});

const {
    collection: sentences,
    totalCount,
    filters,
    loading,
    currentPage,
    pageNumbers,
    goToPage,
    updateFilter,
} = useResourceIndex({
    routeBase: 'sentences',
    defaultFilters: {sort: 'latest'},
});
</script>

<template>
    <div id="app-body">
        <div class="window-container">
            <div class="window-header">
                <RouterLink :to="{ name: 'sentences.index' }" class="material-symbols-rounded">home</RouterLink>
                <div class="window-header-url">www.palweb.app/library/sentences</div>
                <RouterLink v-if="UserStore.isAdmin" :to="{ name: 'speech-maker.sentence' }" class="material-symbols-rounded">
                    add
                </RouterLink>
                <RouterLink :to="{ name: 'sentences.random' }" class="material-symbols-rounded">keyboard_double_arrow_right
                </RouterLink>
            </div>
            <div class="window-section-head"><h1>{{ $t('pages.sentences.index.title') }}</h1></div>
            <div class="window-section-head"><h2>{{ $t('pages.common.index') }}</h2></div>

            <SearchFilters :activeModel="'sentences'" :filters="filters" @updateFilter="updateFilter"/>

            <template v-if="loading">
                <AppTip>
                    <p>{{ $t('pages.common.loading', { model: $t('models.sentences') }) }}</p>
                </AppTip>
                <LoadingSpinner/>
            </template>
            <template v-else>
                <AppTip>
                    <p v-if="totalCount > 0 && !Object.values(filters).every(value => !value)">
                        {{ $t('pages.common.displaying-results', { count: totalCount, model: $t('models.sentences') }) }}
                    </p>
                    <p v-else-if="totalCount > 0">{{ $t('pages.common.displaying-all', { model: $t('models.sentences') }) }}</p>
                    <p v-else>{{ $t('pages.common.displaying-none', { model: $t('models.sentences') }) }}</p>
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
