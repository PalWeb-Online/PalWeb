<script setup>
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import Layout from "../../../Shared/Layout.vue";
import TermItem from "../../../components/TermItem.vue";
import Paginator from "../../../Shared/Paginator.vue";
import AppTip from "../../../components/AppTip.vue";

const props = defineProps({
    filters: Object,
    cards: Array,
    totalCount: Number
})

const setFilter = (key, value) => {
    router.get(route('card-dealer.cards'), {
        ...props.filters,
        [key]: value
    }, {preserveState: true});
}

defineOptions({
    layout: Layout
});
</script>

<template>
    <Head title="Card Dealer: Cards"/>
    <div id="app-head">
        <h1>{{ $t('pages.card-dealer.title') }}</h1>
    </div>
    <div id="app-body">
        <div class="window-container">
            <div class="window-header">
                <Link :href="route('card-dealer.index')" class="material-symbols-rounded">
                    arrow_back
                </Link>
                <div class="window-header-url">www.palweb.app/workbench/card-dealer/cards</div>
            </div>
            <div class="window-section-head">
                <h1>{{ $t('pages.cards.index.title') }}</h1>
            </div>
            <div class="search-filters-container">
                <select :value="filters.status" @change="setFilter('status', $event.target.value)">
                    <option value="">{{ $t('card.fields.status') }}</option>
                    <option value="active">{{ $t('card.status.active') }}</option>
                    <option value="suspended">{{ $t('card.status.suspended') }}</option>
                </select>
                <select :value="filters.level" @change="setFilter('level', $event.target.value)">
                    <option value="">{{ $t('card.fields.mastery-level') }}</option>
                    <option value="0">{{ $t('card.mastery-level.new') }}</option>
                    <option value="1">{{ $t('card.mastery-level.lowest') }}</option>
                    <option value="2">{{ $t('card.mastery-level.low') }}</option>
                    <option value="3">{{ $t('card.mastery-level.medium') }}</option>
                    <option value="4">{{ $t('card.mastery-level.high') }}</option>
                    <option value="5">{{ $t('card.mastery-level.highest') }}</option>
                </select>
                <select :value="filters.sort" @change="setFilter('sort', $event.target.value)">
                    <option value="due">{{ $t('pages.cards.index.filters.due') }}</option>
                    <option value="latest">{{ $t('pages.cards.index.filters.latest') }}</option>
                    <option value="mastery">{{ $t('pages.cards.index.filters.mastery') }}</option>
                </select>
            </div>
            <AppTip>
                <p v-if="totalCount > 0">{{ $t('pages.cards.index.messages.results', { count: totalCount, message: $t('pages.cards.index.sort-message.' + filters.sort) }) }}</p>
                <p v-else-if="!Object.values(filters).every(value => !value)">{{ $t('pages.cards.index.messages.no-results') }}</p>
                <p v-else>{{ $t('pages.cards.index.messages.no-cards') }}</p>
            </AppTip>

            <template v-if="cards.data.length > 0">
                <div class="model-list index-list">
                    <TermItem v-for="card in cards.data"
                              :key="card.id"
                              :model="{ ...card.term, card: card }"
                    />
                </div>
                <Paginator :links="cards.meta.links"/>
            </template>
        </div>
    </div>
</template>
