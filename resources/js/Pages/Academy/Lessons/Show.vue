<script setup>
import Layout from "../../../Shared/Layout.vue";
import DialogContainer from "../../../components/DialogContainer.vue";
import {provide, ref, watch} from "vue";
import {route} from "ziggy-js";
import UnitNav from "../Units/UI/UnitNav.vue";
import SkillContainer from "../Lessons/UI/SkillContainer.vue";
import DeckContainer from "../../../components/DeckContainer.vue";
import ActivityContainer from "../../../components/ActivityContainer.vue";
import AppTip from "../../../components/AppTip.vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    unit: {type: Object, required: false},
    lesson: {type: Object, required: true},
})

const lessonSentences = ref({});
const isLoadingSentences = ref(true);

provide('lessonSentences', lessonSentences);

const currentTab = ref('deck');

const loadSkillSentences = async () => {
    const ids = new Set();
    props.lesson.document?.skills.forEach(skill => {
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
        lessonSentences.value = response.data;

    } catch (error) {
        console.error("Failed to fetch Skill Sentences", error);

    } finally {
        isLoadingSentences.value = false;
    }
}

watch(currentTab, (newTab) => {
    if (newTab === 'skills') loadSkillSentences();
});
</script>
<template>
    <Head :title="`Academy: Lesson ${lesson.global_position}`"/>

    <div id="lesson-nav">
        <UnitNav v-if="unit" :unit="unit" :lesson="lesson" :activeLesson="lesson.global_position"/>
        <div class="lesson-data-container">
            <div class="lesson-data-head">
                <div class="featured-title s">Lesson {{ lesson.global_position }}:</div>
                <div class="lesson-head-title">{{ lesson.title }}</div>
            </div>
            <div class="lesson-data-body">
                <div>{{ lesson.description ?? '(No description has been written for the Lesson yet.)' }}</div>
                <div v-if="lesson.document" class="lesson-skill-summary">
                    <div style="font-weight: 700">In this Lesson, you'll learn to:</div>
                    <ul style="margin-block: 1.6rem">
                        <li v-for="skill in lesson.document.skills">
                            {{ skill.description }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="lesson-stages">
            <div class="lesson-stage-wrapper" @click="currentTab = 'deck'"
                 :class="{active: currentTab === 'deck'}">
                <div class="lesson-stage-index">1</div>
                <div class="featured-title m">deck</div>
            </div>
            <div class="lesson-stage-wrapper" @click="currentTab = 'skills'"
                 :class="{active: currentTab === 'skills', disabled: lesson.progress.stage < 2}">
                <div v-if="lesson.progress.stage < 2" class="lesson-stage-index">
                    <span class="material-symbols-rounded">lock</span>
                </div>
                <template v-else>
                    <div class="lesson-stage-index">2</div>
                    <div class="featured-title m">skills</div>
                </template>
            </div>
            <div class="lesson-stage-wrapper" @click="currentTab = 'dialog'"
                 :class="{active: currentTab === 'dialog', disabled: lesson.progress.stage < 3}">
                <div v-if="lesson.progress.stage < 3" class="lesson-stage-index">
                    <span class="material-symbols-rounded">lock</span>
                </div>
                <template v-else>
                    <div class="lesson-stage-index">3</div>
                    <div class="featured-title m">dialog</div>
                </template>
            </div>
        </div>
    </div>

    <div id="app-body" v-show="currentTab === 'deck'">
        <template v-if="lesson.deck">
            <DeckContainer :model="lesson.deck"/>
        </template>
        <AppTip v-else>
            <p>No Deck available for this Lesson yet.</p>
        </AppTip>
    </div>
    <div id="app-body" v-if="lesson.progress.stage > 1" v-show="currentTab === 'skills'">
        <SkillContainer v-for="skill in lesson.document?.skills" :skill="skill" :key="skill.id"/>

        <template v-if="lesson.activity">
            <div class="featured-title l" style="margin-block: 3.2rem">ready to go?</div>
            <ActivityContainer v-if="lesson.activity" :model="lesson.activity"/>
        </template>
        <AppTip v-else>
            <p>No Activity available for this Lesson yet.</p>
        </AppTip>
    </div>
    <div id="app-body" v-if="lesson.progress.stage > 2" v-show="currentTab === 'dialog'">
        <DialogContainer v-if="lesson.dialog" :model="lesson.dialog"/>
        <AppTip v-else>
            <p>No Dialog available for this Lesson yet.</p>
        </AppTip>
    </div>
</template>

<style lang="scss" scoped>
.lesson-data-container {
    display: grid;
    overflow: hidden;

    @media (min-width: 960px) {
        border-radius: 3.2rem;
    }
}

.lesson-data-head {
    display: grid;
    gap: 1.6rem;
    color: white;
    background: var(--color-medium-secondary);
    padding: 3.2rem 3.2rem 1.6rem;

    .featured-title {
        color: var(--color-accent-medium)
    }

    .lesson-head-title {
        font-family: var(--head-font), sans-serif;
        font-size: 4.8rem;
        font-weight: 700;
        text-transform: none;
        hyphens: none;
    }
}

.lesson-data-body {
    display: grid;
    gap: 3.2rem;
    font-size: 1.8rem;
    line-height: 1.5;
    padding: 3.2rem;
    color: var(--color-dark-primary);
    background: var(--color-accent-light);
}

.lesson-stages {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    justify-items: center;
    margin-block: 3.2rem 6.4rem;

    .lesson-stage-wrapper {
        display: grid;
        justify-items: center;
        align-items: center;
        grid-template-areas: 'overlap';
        user-select: none;
        cursor: pointer;
        font-size: clamp(0.8rem, 2vw, 1.6rem);

        & > * {
            grid-area: overlap;
        }

        .featured-title {
            color: white;
            background: var(--color-medium-secondary);
            font-size: 3em;
            padding: 0.1em 0.3em 0.2em;
            z-index: 1;
        }

        .lesson-stage-index {
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-dark-primary);
            font-family: var(--display-font), serif;
            font-size: 16em;
            height: 0.75em;
            width: 0.75em;
            border-radius: 50%;
            background: var(--color-accent-light);
            overflow: hidden;
            z-index: 0;
            transition: rotate 0.1s;

            .material-symbols-rounded {
                font-size: 0.2em;
            }
        }

        &:not(.disabled) {
            .lesson-stage-index {
                padding-block-end: 0.125em;
            }

            &:hover {
                .lesson-stage-index {
                    rotate: 9deg;
                }
            }
        }

        &.active {
            .featured-title {
                color: var(--color-medium-secondary);
                background: var(--color-accent-light);
            }

            .lesson-stage-index {
                background: var(--color-medium-secondary);
                rotate: 9deg;
            }
        }

        &.disabled {
            .lesson-stage-index {
                color: var(--color-accent-light);
                background: var(--color-dark-primary);
            }

            pointer-events: none;
            cursor: not-allowed;
        }
    }

}
</style>
