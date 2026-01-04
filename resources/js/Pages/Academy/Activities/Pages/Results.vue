<script setup>
import {computed} from "vue";
import {useNavGuard} from "../../../../composables/NavGuard.js";
import ModalWrapper from "../../../../components/Modals/ModalWrapper.vue";
import NavGuard from "../../../../components/Modals/NavGuard.vue";
import ScoreStats from "../../../../components/ScoreStats.vue";
import WindowSection from "../../../../components/WindowSection.vue";
import ScoreDetail from "../../../../components/ScoreDetail.vue";
import {useActivityStore} from "../Stores/ActivityStore.js";
import ActivityBlocksWrapper from "../UI/ActivityBlocksWrapper.vue";
import {route} from "ziggy-js";
import ActivityActions from "../../../../components/Actions/ActivityActions.vue";
import AppTip from "../../../../components/AppTip.vue";
import PopupWindow from "../../../../components/Modals/PopupWindow.vue";

const ActivityStore = useActivityStore();

const hasNavigationGuard = computed(() => {
    return !ActivityStore.isSaved;
});

const {showAlert, handleConfirm, handleCancel, skipNext} = useNavGuard(hasNavigationGuard);

const handleSave = () => {
    skipNext();
    ActivityStore.saveScore();
};
</script>

<template>
    <div class="window-container">
        <div class="window-header">
            <Link :href="route('lessons.show', ActivityStore.data.activity.lesson.global_position)"
                  class="material-symbols-rounded">close
            </Link>
            <div class="window-header-url">www.palweb.app/academy/lessons/{lesson}/activity</div>
        </div>
        <div class="window-section-head">
            <h1>activity</h1>
            <ActivityActions :model="ActivityStore.data.activity"/>
        </div>
        <div class="window-content-head">
            <div class="window-content-head-title">{{ ActivityStore.data.activity?.title }}</div>
        </div>
        <WindowSection :visible="false">
            <template #title>
                <h2>stats</h2>
            </template>
            <template #content>
                <ScoreStats :model="ActivityStore.data.activity"/>
            </template>
        </WindowSection>

        <div class="window-section-head">
            <h2>Results</h2>
            <PopupWindow title="Activity (Results)">
                <div class="h1">Results</div>
                <p>Sometimes humans are smarter than machines. When it comes to input-type exercises, I've provided the
                    application with one or more possible valid answers to match your response against, but your input
                    may still be marked as incorrect for nit-picky reasons like typos or orthographical inconsistencies
                    in terms of things like the <b>hamze</b>, <b>šadde</b> & <b>tanwīn</b>. While it is good to learn
                    what these represent, why they are used & the norms surrounding their usage, it would not be fair to
                    get a wrong answer based on something that is not being tested for. That's why I've provided a
                    <b>Mark as Correct</b> option, so that you can be a part of the grading process. After comparing
                    your answer to the one expected by the application, you can override the result if you think it
                    should have been accepted.</p>
                <p>Remember that your Score is not saved automatically, so you can adjust the results as needed before
                    saving your Score manually. You can also exit the Results page without saving if you want to discard
                    the Score.</p>
            </PopupWindow>
        </div>
        <AppTip>
            <p>If you think one of your answers should have been accepted, feel free to <b>Mark as Correct</b> (see <b>Help</b>).
            </p>
        </AppTip>

        <ScoreDetail :score="ActivityStore.score" :model="ActivityStore.data.activity"/>
        <div class="window-footer">
            <button @click="handleSave" :disabled="ActivityStore.isSaved">save & quit</button>
        </div>
    </div>

    <div class="activity-container">
        <ActivityBlocksWrapper :blocks="ActivityStore.data.activity.document.blocks"/>
    </div>

    <ModalWrapper v-model="showAlert">
        <NavGuard
            message="You haven't saved your Score yet. Are you sure you want to leave this page? Your Score for this Activity will not be saved."
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>
</template>
