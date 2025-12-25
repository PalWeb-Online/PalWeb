<script setup>
import Layout from "../../../Shared/Layout.vue";
import DialogContainer from "../../../components/DialogContainer.vue";
import {onMounted, provide, ref} from "vue";
import {route} from "ziggy-js";
import UnitNav from "../Units/UI/UnitNav.vue";
import AppTip from "../../../components/AppTip.vue";
import SkillContainer from "../Lessons/UI/SkillContainer.vue";
import DeckContainer from "../../../components/DeckContainer.vue";
import ScoreStats from "../../../components/ScoreStats.vue";
import WindowSection from "../../../components/WindowSection.vue";
import ActivityActions from "../../../components/Actions/ActivityActions.vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    lesson: Object,
})

const sentenceLookup = ref({});
const isLoadingSentences = ref(true);

provide('lessonSentences', sentenceLookup);

const currentTab = ref('deck');

onMounted(async () => {
    const ids = new Set();
    props.lesson.data.document?.skills.forEach(skill => {
        skill.blocks.forEach(block => {
            if (block.type === 'sentence' && block.model?.id) {
                ids.add(block.model.id);
            }
        });
    });

    if (ids.size === 0) {
        isLoadingSentences.value = false;
        return;
    }

    try {
        const response = await axios.post(route('sentences.get-many'), {
            ids: Array.from(ids)
        });
        sentenceLookup.value = response.data;

    } catch (error) {
        console.error("Failed to fetch lesson sentences", error);

    } finally {
        isLoadingSentences.value = false;
    }
});
</script>
<template>
    <Head :title="`Academy: Lesson ${lesson.data.slug}`"/>

    <div id="lesson-nav">
        <UnitNav v-if="lesson.data.unit" :unit="lesson.data.unit" :activeSlug="lesson.data.slug"/>

        <div class="lesson-head">
            <div class="featured-title m">Lesson {{ lesson.data.slug }}</div>
            <div class="lesson-head-title">{{ lesson.data.title }}</div>
        </div>
        <div class="unit-meta">
            <div>{{ lesson.data.published ? '' : 'not' }} published</div>
            <Link :href="route('lesson-planner.lesson', lesson.data)">edit</Link>
        </div>

        <AppTip>
            <p v-if="lesson.data.progress.stage === 1">Get a Score of 100% on this Lesson's Deck 3 times to unlock the
                Skills!</p>
            <p v-if="lesson.data.progress.stage === 2">Get a Score of 100% on this Lesson's Activity to complete the
                Lesson!</p>
            <p v-if="lesson.data.progress.stage === 3">You've completed this Lesson! Use the Dialog to practice what
                you've learned!</p>
        </AppTip>

        <div class="lesson-stages">
            <div class="lesson-stage-wrapper" @click="currentTab = 'deck'"
                 :class="{active: currentTab === 'deck'}">
                <div class="lesson-stage-index">1</div>
                <div class="featured-title m">deck</div>
            </div>
            <div class="lesson-stage-wrapper" @click="currentTab = 'skills'"
                 :class="{active: currentTab === 'skills', disabled: lesson.data.progress.stage < 2}">
                <div v-if="lesson.data.progress.stage < 2" class="lesson-stage-index">
                    <span class="material-symbols-rounded">lock</span>
                </div>
                <template v-else>
                    <div class="lesson-stage-index">2</div>
                    <div class="featured-title m">skills</div>
                </template>
            </div>
            <div class="lesson-stage-wrapper" @click="currentTab = 'dialog'"
                 :class="{active: currentTab === 'dialog', disabled: lesson.data.progress.stage < 3}">
                <div v-if="lesson.data.progress.stage < 3" class="lesson-stage-index">
                    <span class="material-symbols-rounded">lock</span>
                </div>
                <template v-else>
                    <div class="lesson-stage-index">3</div>
                    <div class="featured-title m">dialog</div>
                </template>
            </div>
            <!--            <div class="lesson-model-score-count material-symbols-rounded"-->
            <!--                 v-if="lesson.data.progress.stage >= 2"-->
            <!--            >-->
            <!--                <div :class="{ completed: lesson.data.progress.scores_count.activity > 0 }">check</div>-->
            <!--            </div>-->
        </div>
    </div>

    <div id="app-body" v-show="currentTab === 'deck'">
        <template v-if="lesson.data.deck">
            <div class="lesson-model-progress-wrapper">
                <div></div>
                <div>
                    <div class="lesson-model-score-count">
                        <div class="material-symbols-rounded"
                             :class="{ completed: lesson.data.progress.scores_count.deck > 0 }">check
                        </div>
                        <div class="material-symbols-rounded"
                             :class="{ completed: lesson.data.progress.scores_count.deck > 1 }">check
                        </div>
                        <div class="material-symbols-rounded"
                             :class="{ completed: lesson.data.progress.scores_count.deck > 2 }">check
                        </div>
                    </div>
                    <Link :href="route('deck-master.study', lesson.data.deck.id)">study</Link>
                </div>
            </div>
            <DeckContainer :model="lesson.data.deck"/>
        </template>
        <template v-else>
            No Deck available for this Lesson yet.
        </template>
    </div>
    <div id="app-body" v-show="currentTab === 'skills'">
        <template v-if="lesson.data.document">
            <SkillContainer v-for="skill in lesson.data.document.skills" :skill="skill" :key="skill.id"/>
        </template>

        <template v-if="lesson.data.activity">
            <div class="featured-title l">ready to go?</div>
            <div class="window-container">
                <div class="window-header">
                    <div class="material-symbols-rounded">home</div>
                    <div class="window-header-url">www.palweb.app/academy/lessons/{lesson}</div>
                </div>
                <div class="window-section-head">
                    <h1>activity</h1>
                    <ActivityActions :model="lesson.data.activity"/>
                </div>
                <div class="window-content-head">
                    <div class="window-content-head-title">{{ lesson.data.activity.title }}</div>
                </div>
                <WindowSection>
                    <template #title>
                        <h2>stats</h2>
                    </template>
                    <template #content>
                        <ScoreStats :model="lesson.data.activity"/>
                    </template>
                </WindowSection>

                <div class="window-footer">
                    <Link :href="route('activities.show', lesson.data.slug)">
                        Start Activity
                    </Link>
                </div>
            </div>
        </template>

        <p v-else>
            No Activity available for this Lesson yet.
        </p>
    </div>
    <div id="app-body" v-show="currentTab === 'dialog'">
        <DialogContainer v-if="lesson.data.dialog" :model="lesson.data.dialog"/>
        <p v-else>
            No Dialog available for to this Lesson yet.
        </p>
    </div>
</template>
