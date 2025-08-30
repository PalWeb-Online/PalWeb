<script setup>
import Layout from "../../../Shared/Layout.vue";
import {useQuizzerStore} from "./Stores/QuizzerStore.js";
import Settings from "./Pages/Settings.vue";
import Quiz from "./Pages/Quiz.vue";
import Results from "./Pages/Results.vue";
import {onMounted} from "vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    model: Object,
    modelType: String,
});

const QuizzerStore = useQuizzerStore();

onMounted(() => {
    QuizzerStore.reset();
    QuizzerStore.data.model = props.model;
    QuizzerStore.settings.modelType = props.modelType;
});
</script>
<template>
    <Head :title="`Academy: Quizzer (${modelType})`"/>
    <div id="app-body">
        <div class="quizzer-title featured-title l">Quizzer</div>
        <Settings v-if="QuizzerStore.data.step ==='settings'"/>
        <Quiz v-if="QuizzerStore.data.step ==='quiz'"/>
        <Results v-if="QuizzerStore.data.step ==='results'"/>
    </div>
</template>
