<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import ScoreStats from "../../../components/ScoreStats.vue";
import ScoreItem from "../../../components/ScoreItem.vue";
import AppTip from "../../../components/AppTip.vue";
import PinButton from "../../../components/PinButton.vue";
import DeckActions from "../../../components/Actions/DeckActions.vue";
import WindowSection from "../../../components/WindowSection.vue";
import Paginator from "../../../Shared/Paginator.vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    model: Object,
    scorable_type: String,
    scores: Array,
    totalCount: Number,
})
</script>
<template>
    <Head :title="`Academy: Score History for ${model.id}`"/>
    <div id="app-head">
        <Link :href="route('scores.history', { scorable_type: 'deck', scorable_id: model.id })"><h1>my Progress</h1>
        </Link>
    </div>
    <div id="app-body">
        <div id="quizzer-container" class="window-container">
            <div class="window-header">
                <Link :href="route('scores.index')" class="material-symbols-rounded">home</Link>
                <div class="window-header-url">www.palweb.app/academy/scores/history/{deck}</div>
            </div>
            <div class="window-section-head">
                <h1>Deck</h1>
                <PinButton :modelType="scorable_type" :model="model"/>
                <DeckActions :model="model"/>
            </div>
            <div class="window-content-head">
                <div class="window-content-head-title">{{ model.name }}</div>
            </div>
            <WindowSection>
                <template #title>
                    <h2>stats</h2>
                </template>
                <template #content>
                    <ScoreStats :model="model"/>
                </template>
            </WindowSection>

            <!--            -->
            <div class="window-section-head">
                <h2>History</h2>
            </div>
            <AppTip>
                <p v-if="totalCount > 0">Displaying all {{ totalCount }} Scores for this
                    <span style="text-transform: capitalize">{{ scorable_type }}</span>,
                    from most to least recent.</p>
                <p v-else>You have not Quizzed this <span style="text-transform: capitalize">{{ scorable_type }}</span>
                    yet. When you do, you will see a history of your Scores here.
                    <Link :href="route('quizzer.show', { scorable_type: scorable_type, scorable_id: model.id })">Go to
                        the Quizzer.
                    </Link>
                </p>
            </AppTip>
            <template v-if="totalCount > 0">
                <ScoreItem v-for="score in scores.data" :score="score" :key="score.id"/>
                <Paginator :links="scores.meta.links"/>
            </template>
            <!--            -->
        </div>
    </div>
</template>
