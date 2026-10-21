<script setup>
import TermItem from "../../../components/TermItem.vue";
import Paginator from "../../../Shared/Paginator.vue";
import AppTip from "../../../components/AppTip.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import {useResourceIndex} from "../../../composables/resources/useResourceIndex.js";
import {computed} from "vue";

const {
    collection: cards,
    totalCount,
    filters,
    loading,
    currentPage,
    pageNumbers,
    goToPage,
    updateFilter,
} = useResourceIndex({
    routeBase: 'cards',
    defaultFilters: {sort: 'due', status: '', level: ''},
});

const hasCardFilters = computed(() => Boolean(filters.value.status || filters.value.level));
</script>

<template>
    <div id="app-head">
        <h1>{{ $t('pages.card-dealer.title') }}</h1>
    </div>
    <div id="app-body">
        <div class="window-container">
            <div class="window-header">
                <RouterLink :to="{ name: 'card-dealer.index' }" class="material-symbols-rounded">
                    arrow_back
                </RouterLink>
                <div class="window-header-url">www.palweb.app/workbench/card-dealer/cards</div>
            </div>
            <div class="window-section-head">
                <h1>{{ $t('pages.cards.index.title') }}</h1>
            </div>
            <div class="search-filters-container">
                <select v-model="filters.status" :class="filters.status ? 'persisting' : ''"
                        @change="updateFilter({filter: 'status', value: filters.status})">
                    <option value="">{{ $t('card.fields.status') }}</option>
                    <option value="active">{{ $t('card.status.active') }}</option>
                    <option value="suspended">{{ $t('card.status.suspended') }}</option>
                </select>
                <select v-model="filters.level" :class="filters.level ? 'persisting' : ''"
                        @change="updateFilter({filter: 'level', value: filters.level})">
                    <option value="">{{ $t('card.fields.mastery-level') }}</option>
                    <option value="0">{{ $t('card.mastery-level.new') }}</option>
                    <option value="1">{{ $t('card.mastery-level.lowest') }}</option>
                    <option value="2">{{ $t('card.mastery-level.low') }}</option>
                    <option value="3">{{ $t('card.mastery-level.medium') }}</option>
                    <option value="4">{{ $t('card.mastery-level.high') }}</option>
                    <option value="5">{{ $t('card.mastery-level.highest') }}</option>
                </select>
                <select v-model="filters.sort" @change="updateFilter({filter: 'sort', value: filters.sort})">
                    <option value="due">{{ $t('pages.cards.index.filters.due') }}</option>
                    <option value="latest">{{ $t('pages.cards.index.filters.latest') }}</option>
                    <option value="mastery">{{ $t('pages.cards.index.filters.mastery') }}</option>
                </select>
            </div>
            <template v-if="loading">
                <AppTip>
                    <p>{{ $t('pages.common.loading', { model: $t('models.cards') }) }}</p>
                </AppTip>
                <LoadingSpinner/>
            </template>
            <template v-else>
                <AppTip>
                    <p v-if="totalCount > 0">{{ $t('pages.cards.index.messages.results', { count: totalCount, message: $t('pages.cards.index.sort-message.' + filters.sort) }) }}</p>
                    <p v-else-if="hasCardFilters">{{ $t('pages.cards.index.messages.no-results') }}</p>
                    <p v-else>{{ $t('pages.cards.index.messages.no-cards') }}</p>
                </AppTip>

                <template v-if="cards.data.length > 0">
                    <div class="model-list index-list">
                        <TermItem v-for="card in cards.data" :key="card.id"
                                  :model="{ ...card.term, card: card }"
                        />
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
