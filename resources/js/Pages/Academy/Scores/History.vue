<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import ScoreStats from "../../../components/ScoreStats.vue";
import AppTip from "../../../components/AppTip.vue";
import PinButton from "../../../components/PinButton.vue";
import DeckActions from "../../../components/Actions/DeckActions.vue";
import WindowSection from "../../../components/WindowSection.vue";
import Paginator from "../../../Shared/Paginator.vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";
import PurgeScores from "../../../components/Modals/PurgeScores.vue";
import {nextTick, onMounted, ref, watch} from "vue";
import ScoreDetail from "../../../components/ScoreDetail.vue";
import {router} from "@inertiajs/vue3";

defineOptions({
    layout: Layout
});

const props = defineProps({
    model: Object,
    scorable_type: String,
    scores: Array,
    totalCount: Number,
    selectedScore: Object,
})

const showPurgeScores = ref(false);

const formatter = new Intl.NumberFormat('en-US', {
    style: 'percent',
    maximumFractionDigits: 0,
});

const deleteScore = (id) => {
    if (!confirm('Are you sure you want to delete this Score?')) return;
    router.delete(route('scores.destroy', id));
}

function scrollToDetail() {
    nextTick(() => {
        const el = document.querySelector('#score-detail');
        if (el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
}

onMounted(() => {
    if (props.selectedScore) {
        scrollToDetail();
    }
});

watch(() => props.selectedScore, (newVal) => {
    if (newVal) {
        scrollToDetail();
    }
});
</script>
<template>
    <Head :title="`Academy: Score History for ${model.id}`"/>
    <div id="app-head">
        <Link :href="route('scores.index')"><h1>Scores</h1></Link>
    </div>
    <div id="app-body">
        <div id="quizzer-container" class="window-container">
            <div class="window-header">
                <Link :href="route('scores.index')" class="material-symbols-rounded">home</Link>
                <div class="window-header-url">www.palweb.app/academy/scores/history/{{ '{'+scorable_type+'}'}}</div>
            </div>
            <div class="window-section-head">
                <h1>{{ scorable_type }}</h1>
                <PinButton :modelType="scorable_type" :model="model"/>
                <DeckActions v-if="scorable_type === 'deck'" :model="model"/>
            </div>
            <div class="window-content-head">
                <div class="window-content-head-title">{{ scorable_type === 'deck' ? model.name : model.title }}</div>
            </div>
            <WindowSection>
                <template #title>
                    <h2>stats</h2>
                </template>
                <template #content>
                    <ScoreStats :model="model"/>
                </template>
            </WindowSection>

            <template v-if="selectedScore">
                <div class="window-section-head" id="score-detail">
                    <Link
                        :href="route('scores.history', { scorable_type: selectedScore?.scorable_type, scorable_id: selectedScore?.scorable_id })"
                        class="material-symbols-rounded" preserve-scroll preserve-state>
                        arrow_back
                    </Link>
                    <h2>Detail</h2>
                    <button @click="deleteScore(selectedScore?.id)" class="material-symbols-rounded">delete</button>
                </div>
                <ScoreDetail :score="selectedScore"/>
            </template>
            <template v-else>
                <div class="window-section-head">
                    <h2>History</h2>
                    <button v-if="totalCount > 0" @click="showPurgeScores = true" class="material-symbols-rounded">delete_sweep</button>
                </div>
                <AppTip>
                    <p v-if="totalCount > 0">Displaying all {{ totalCount }} Scores for this
                        <span style="text-transform: capitalize">{{ scorable_type }}</span>,
                        from most to least recent.</p>
                    <p v-else>You have not Quizzed this <span style="text-transform: capitalize">{{
                            scorable_type
                        }}</span>
                        yet. When you do, you will see a history of your Scores here.
                    </p>
                </AppTip>
                <template v-if="totalCount > 0">
                    <div class="score-item-wrapper" v-for="score in scores.data">
                        <Link :href="route('scores.history', { scorable_type: score.scorable_type, scorable_id: score.scorable_id, score: score.id })"
                              class="score-item" preserve-scroll preserve-state>
                            <div style="text-transform: capitalize">{{ score.settings.quizType }}</div>
                            <div>{{ formatter.format(score.score) }} ({{
                                    score.results.filter(q => q.correct).length
                                }}/{{ score.results.length }})
                            </div>
                            <div style="font-size: 1.2rem">{{ score.created_at }}</div>
                        </Link>
                        <button @click="deleteScore(score.id)" class="material-symbols-rounded">delete</button>
                    </div>
                    <Paginator :links="scores.meta.links"/>
                </template>
            </template>
        </div>
    </div>

    <ModalWrapper v-model="showPurgeScores">
        <PurgeScores :scorable_type="scorable_type" :scorable_id="model.id"/>
    </ModalWrapper>
</template>
