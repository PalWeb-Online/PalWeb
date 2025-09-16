<script setup>
import Layout from "../../../Shared/Layout.vue";
import {useQuizzerStore} from "./Stores/QuizzerStore.js";
import Settings from "./Pages/Settings.vue";
import Quiz from "./Pages/Quiz.vue";
import Results from "./Pages/Results.vue";
import {onMounted, watch} from "vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    model: Object,
    scorable_type: String,
});

const QuizzerStore = useQuizzerStore();

onMounted(() => {
    QuizzerStore.reset();
    QuizzerStore.data.model = props.model;
    QuizzerStore.data.scorable_type = props.scorable_type;
});

watch(() => props.model, (newModel) => {
    QuizzerStore.data.model = newModel;
}, {
    deep: true
});

</script>
<template>
    <Head :title="`Academy: Quizzer (${scorable_type})`"/>
    <div id="app-body">
        <div class="quizzer-title featured-title l">Quizzer</div>
        <Settings v-if="QuizzerStore.data.step ==='settings'"/>
        <Quiz v-if="QuizzerStore.data.step ==='quiz'"/>
        <Results v-if="QuizzerStore.data.step ==='results'"/>
    </div>
</template>
