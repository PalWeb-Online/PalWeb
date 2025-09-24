<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import DeckItem from "../../../components/DeckItem.vue";
import AppTip from "../../../components/AppTip.vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    decks: Object,
})
</script>
<template>
    <Head title="Academy: myProgress"/>
    <div id="app-head">
        <Link :href="route('scores.index')"><h1>my Progress</h1></Link>
    </div>
    <div id="app-body">
        <div class="app-body-section">
            <div class="featured-title l" style="text-transform: none">Lessons</div>
            <AppTip>
                <p>Lessons & progress tracking will be available by December 31, 2025.</p>
            </AppTip>

            <div class="model-list" style="margin-block-start: 6.4rem">
                <div class="featured-title m" style="text-transform: none">Latest Scored</div>
                <DeckItem v-for="deck in decks" :model="deck" :key="deck.id" target="academy"/>
            </div>
        </div>
    </div>
</template>
