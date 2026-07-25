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
        <Link :href="route('scores.index')"><h1>{{ $t('pages.scores.index.title') }}</h1></Link>
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
            <template v-if="mode === 'decks'">
                <AppTip>
                    <p>{{ $t('pages.scores.index.messages.deck-list') }}</p>
                </AppTip>

                <div class="featured-title m" style="text-transform: none">{{ $t('pages.scores.index.latest-scored') }}</div>
                <div class="model-list">
                    <AppTip v-if="!totalCount">
                        <p>{{ $t('pages.scores.index.messages.no-decks') }}</p>
                    </AppTip>
                    <DeckItem v-for="deck in latestScoredDecks.data" :model="deck" :key="deck.id" target="academy"/>
                    <Paginator :links="latestScoredDecks.meta.links"/>
                </div>
            </template>
            <template v-if="mode === 'lessons'">
                <AppTip v-if="scoredLessonModels.length === 0">
                    <p>You don't have any Scores for any Lesson Decks or Activities. Have you started the Lessons in the
                        Academy yet? If not, <Link :href="route('units.index')">click here</Link> to get started!
                    </p>
                </AppTip>
                <template v-else v-for="(lesson, slug) in scoredLessonModels">
                    <div class="featured-title m">{{ $t('components.lesson.number', {number: slug}) }}</div>
                    <div class="model-list">
                        <DeckItem v-if="lesson.deck" :model="lesson.deck" target="academy"/>
                        <ActivityItem v-if="lesson.activity" :model="lesson.activity" target="academy"/>
                    </div>
                </template>
            </template>
        </div>
    </div>
</template>
