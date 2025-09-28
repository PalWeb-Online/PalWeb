<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import DeckItem from "../../../components/DeckItem.vue";
import AppTip from "../../../components/AppTip.vue";
import Paginator from "../../../Shared/Paginator.vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    decks: Object,
    totalCount: Number,
})
</script>
<template>
    <Head title="Academy: myProgress"/>
    <div id="app-head">
        <Link :href="route('scores.index')"><h1>my Progress</h1></Link>
    </div>
    <div id="app-body">
        <div class="app-body-section">
            <div class="model-list">
                <div class="featured-title l" style="text-transform: none">Lessons</div>
                <AppTip>
                    <p>Lessons & progress tracking will be available by December 31, 2025.</p>
                </AppTip>
            </div>

            <div class="model-list" style="margin-block-start: 6.4rem">
                <div class="featured-title m" style="text-transform: none">Latest Scored</div>
                <AppTip v-if="!totalCount">
                    <p>You have not Quizzed anything yet. When you do, you will see your most recently Scored models
                        here.</p>
                </AppTip>
                <DeckItem v-for="deck in decks.data" :model="deck" :key="deck.id" target="academy"/>
            </div>
            <Paginator :links="decks.meta.links"/>
        </div>
    </div>
</template>
