<script setup>
import Layout from "../../../Shared/Layout.vue";
import DeckItem from "../../../components/DeckItem.vue";
import AppTip from "../../../components/AppTip.vue";
import SearchFilters from "../../../Shared/SearchFilters.vue";
import {useUserStore} from "../../../stores/UserStore.js";
import Paginator from "../../../Shared/Paginator.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import {useResourceIndex} from "../../../composables/resources/useResourceIndex.js";

const UserStore = useUserStore();
defineOptions({layout: Layout});

const {
    collection: decks,
    totalCount,
    filters,
    loading,
    currentPage,
    pageNumbers,
    goToPage,
    updateFilter,
} = useResourceIndex({
    routeBase: 'decks',
    defaultFilters: {sort: 'latest'},
});
</script>

<template>
    <div id="app-body">
        <div class="window-container">
            <div class="window-header">
                <RouterLink :to="{ name: 'decks.index' }" class="material-symbols-rounded">home</RouterLink>
                <div class="window-header-url">www.palweb.app/library/decks</div>
                <RouterLink v-if="UserStore.isUser" :to="{ name: 'deck-master.build' }" class="material-symbols-rounded">add
                </RouterLink>
                <RouterLink :to="{ name: 'decks.random' }" class="material-symbols-rounded">keyboard_double_arrow_right</RouterLink>
            </div>
            <div class="window-section-head"><h1>{{ $t('pages.decks.index.title') }}</h1></div>
            <div class="window-section-head"><h2>{{ $t('pages.common.index') }}</h2></div>

            <SearchFilters :activeModel="'decks'" :filters="filters" @updateFilter="updateFilter"/>

            <template v-if="loading">
                <AppTip>
                    <p>{{ $t('pages.common.loading', { model: $t('models.decks') }) }}</p>
                </AppTip>
                <LoadingSpinner/>
            </template>
            <template v-else>
                <AppTip>
                    <p v-if="totalCount > 0 && !Object.values(filters).every(value => !value)">
                        {{ $t('pages.common.displaying-results', { count: totalCount, model: $t('models.decks') }) }}
                        {{ $t(`pages.common.sort-messages.${filters.sort}`) }}
                    </p>
                    <p v-else-if="totalCount > 0">{{ $t('pages.common.displaying-all', { model: $t('models.decks') }) }}</p>
                    <p v-else>{{ $t('pages.common.displaying-none', { model: $t('models.decks') }) }}</p>
                </AppTip>
                <template v-if="totalCount > 0">
                    <div class="model-list index-list">
                        <DeckItem v-for="deck in decks.data" :key="deck.id" :model="deck"/>
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
