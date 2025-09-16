<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import PinButton from "../../../components/PinButton.vue";
import ScoreStats from "../../../components/ScoreStats.vue";
import DeckActions from "../../../components/Actions/DeckActions.vue";
import ScoreDetail from "../../../components/ScoreDetail.vue";
import WindowSection from "../../../components/WindowSection.vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    model: Object,
    score: Object,
})
</script>
<template>
    <Head title="Academy: myProgress"/>
    <div id="app-head">
        <Link :href="route('scores.show', score.id)"><h1>my Progress</h1></Link>
    </div>
    <div id="app-body">
        <div id="quizzer-container" class="window-container">
            <div class="window-header">
                <Link :href="route('scores.index')" class="material-symbols-rounded">home</Link>
                <div class="window-header-url">www.palweb.app/academy/scores/history/{deck}</div>
            </div>
            <div class="window-section-head">
                <h1>Deck</h1>
                <PinButton :modelType="score.scorable_type" :model="model"/>
                <DeckActions :model="model"/>
            </div>
            <div class="window-content-head">
                <div class="window-content-head-title">{{ model.name }}</div>
            </div>
            <WindowSection :visible="false">
                <template #title>
                    <h2>stats</h2>
                </template>
                <template #content>
                    <ScoreStats :model="model"/>
                </template>
            </WindowSection>

            <!--            -->
            <div class="window-section-head">
                <h2>Detail</h2>
                <Link
                    :href="route('scores.history', { scorable_type: score.scorable_type, scorable_id: score.scorable_id })"
                    class="material-symbols-rounded">
                    close
                </Link>
            </div>
            <ScoreDetail :score="score"/>
            <!--            -->
        </div>
    </div>
</template>
