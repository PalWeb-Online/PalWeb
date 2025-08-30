<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import ScoreStats from "../../../components/ScoreStats.vue";
import ScoreItem from "../../../components/ScoreItem.vue";
import AppTip from "../../../components/AppTip.vue";
import PinButton from "../../../components/PinButton.vue";
import DeckActions from "../../../components/Actions/DeckActions.vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    model: Object,
})
</script>
<template>
    <Head :title="`Academy: Score History for ${model.id}`"/>
    <div id="app-head">
        <Link :href="route('scores.history.deck', model.id)"><h1>my Progress</h1></Link>
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
                <h2>Score History</h2>
            </div>
            <ScoreItem v-if="model.scores.length" v-for="score in model.scores" :score="score" :key="score.id"/>
            <AppTip v-else>
                <p>You have not Quizzed this Deck yet. When you do, you will see a history of your Scores here.
                    <Link :href="route('quizzer.deck', model.id)">Go to the Quizzer.</Link>
                </p>
            </AppTip>
            <!--            todo: paginate -->
<!--            -->
        </div>
    </div>
</template>
