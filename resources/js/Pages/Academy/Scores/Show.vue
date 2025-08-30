<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import PinButton from "../../../components/PinButton.vue";
import ScoreStats from "../../../components/ScoreStats.vue";
import DeckActions from "../../../components/Actions/DeckActions.vue";
import QuizResults from "../../../components/QuizResults.vue";

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
                <PinButton modelType="deck" :model="model"/>
                <DeckActions :model="model"/>
            </div>
            <div class="window-content-head">
                <div class="window-content-head-title">{{ model.name }}</div>
            </div>
            <ScoreStats :model="model"/>

<!--            -->
            <div class="window-section-head">
                <Link :href="route('scores.history.deck', score.scorable_id)" class="material-symbols-rounded">arrow_back</Link>
                <h2>Detail</h2>
            </div>
            <QuizResults :settings="score.settings" :score="score.score" :results="score.results"/>
<!--            -->
        </div>
    </div>
</template>
