<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import DeckItem from "../../../components/DeckItem.vue";
import AppTip from "../../../components/AppTip.vue";
import Paginator from "../../../Shared/Paginator.vue";
import ActivityItem from "../../../components/ActivityItem.vue";
import {ref} from "vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    scoredLessonModels: Object,
    latestScoredDecks: Object,
    totalCount: Number,
})

const mode = ref('decks');
</script>
<template>
    <Head title="Academy: Scores"/>
    <div id="app-head">
        <Link :href="route('scores.index')"><h1>Scores</h1></Link>
    </div>
    <div id="app-body">
        <div class="app-body-section">
            <div class="sm-mode-select">
                <button class="featured-title l" :class="{'active': mode === 'decks'}"
                        @click="mode = 'decks'">dck
                </button>
                <button class="featured-title l" :class="{'active': mode === 'lessons'}"
                        @click="mode = 'lessons'">lsn
                </button>
            </div>

            <template v-if="mode === 'lessons'" v-for="(lesson, slug) in scoredLessonModels">
                <div class="featured-title m">Lesson {{ slug }}</div>
                <div class="model-list">
                    <DeckItem v-if="lesson.deck" :model="lesson.deck" target="academy"/>
                    <ActivityItem v-if="lesson.activity" :model="lesson.activity" target="academy"/>
                </div>
            </template>

            <template v-if="mode === 'decks'">
                <AppTip>
                    <p>Here are all the Decks you've studied, from most to least recent, except for Decks associated
                        with Lessons, which are shown in the Lessons tab.</p>
                </AppTip>

                <div class="featured-title m" style="text-transform: none">Latest Scored</div>
                <div class="model-list">
                    <AppTip v-if="!totalCount">
                        <p>You have not Quizzed anything yet. When you do, you will see your most recently Scored models
                            here.</p>
                    </AppTip>
                    <DeckItem v-for="deck in latestScoredDecks.data" :model="deck" :key="deck.id" target="academy"/>
                    <Paginator :links="latestScoredDecks.meta.links"/>
                </div>
            </template>
        </div>
    </div>
</template>
