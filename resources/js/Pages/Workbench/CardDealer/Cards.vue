<script setup>
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {computed} from "vue";
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

const sortingMessage = computed(() => {
    if (props.filters.sort === 'latest') {
        return 'sorted by most recent review';
    } else if (props.filters.sort === 'mastery') {
        return 'sorted by Mastery Level';
    } else {
        return 'sorted by soonest due';
    }
});
</script>

<template>
    <Head title="Card Dealer: Cards"/>
    <div id="app-head">
        <h1>Card Dealer</h1>
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
                <h1>cards</h1>
            </div>
            <div class="search-filters-container">
                <select :value="filters.status" @change="setFilter('status', $event.target.value)">
                    <option value="">All</option>
                    <option value="active">Active</option>
                    <option value="suspended">Suspended</option>
                </select>
                <select :value="filters.level" @change="setFilter('level', $event.target.value)">
                    <option value="">All</option>
                    <option value="0">New</option>
                    <option value="1">Rusty</option>
                    <option value="2">Learning</option>
                    <option value="3">Solid</option>
                    <option value="4">Confident</option>
                    <option value="5">Mastered</option>
                </select>
                <select :value="filters.sort" @change="setFilter('sort', $event.target.value)">
                    <option value="due">by Soonest Due</option>
                    <option value="latest">by Latest Review</option>
                    <option value="mastery">by Mastery Level</option>
                </select>
            </div>
            <AppTip>
                <p v-if="totalCount > 0">Displaying {{ totalCount }} Cards, {{ sortingMessage }}.</p>
                <p v-else-if="!Object.values(filters).every(value => !value)">You have no Cards matching these filters.</p>
                <p v-else>You don't have any Cards yet. Start reviewing to get started!</p>
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
