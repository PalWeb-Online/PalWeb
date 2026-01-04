<script setup>
import Layout from "../../../Shared/Layout.vue";
import {computed, onBeforeUnmount, onMounted, watch} from "vue";
import Results from "./Pages/Results.vue";
import {useActivityStore} from "./Stores/ActivityStore.js";
import ActivityBlocksWrapper from "./UI/ActivityBlocksWrapper.vue";
import NavGuard from "../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";
import {route} from "ziggy-js";
import {useNavGuard} from "../../../composables/NavGuard.js";

defineOptions({
    layout: Layout
});

const props = defineProps({
    activity: Object,
})

const ActivityStore = useActivityStore();

const hasNavigationGuard = computed(() => {
    return ActivityStore.data.step === 'activity';
});

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

const isValidRequest = computed(() => {
    if (!ActivityStore.exercises.length) return;

    return !ActivityStore.exercises.some(e => {
        if (e.response === null || e.response === undefined) return true;

        if (typeof e.response === 'string' || Array.isArray(e.response)) {
            return e.response.length === 0;
        }

        return false;
    })
});

onMounted(() => {
    ActivityStore.reset();
    ActivityStore.data.activity = props.activity;
    ActivityStore.startActivity();
});

onBeforeUnmount(() => {
    ActivityStore.reset();
});

watch(() => props.activity, (newActivity) => {
    ActivityStore.data.activity = newActivity;
}, {
    deep: true
});
</script>

<template>
    <Head title="Activity"/>
    <div class="activity-head" v-if="ActivityStore.data.step === 'activity'">
        <Link class="feature-callout" style="justify-self: center"
              :href="route('lessons.show', activity.lesson.global_position)">
            Exit to Lesson
        </Link>
        <h1>activity</h1>
    </div>
    <div id="app-body">
        <div class="activity-container" v-if="ActivityStore.data.step === 'activity'">
            <ActivityBlocksWrapper v-if="!ActivityStore.data.isLoading"
                                   :blocks="ActivityStore.data.activity.document.blocks"/>
            <button class="material-symbols-rounded" :disabled="!isValidRequest"
                    @click="ActivityStore.submitActivity">
                check
            </button>
        </div>

        <Results v-if="ActivityStore.data.step === 'results'"/>
    </div>

    <ModalWrapper v-model="showAlert">
        <NavGuard
            message="You haven't finished the Activity yet. Are you sure you want to leave this page? Your progress will not be saved."
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>
</template>
