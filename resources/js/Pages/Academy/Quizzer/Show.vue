<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import {useQuizzerStore} from "./Stores/QuizzerStore.js";
import Setup from "./Pages/Setup.vue";
import Quiz from "./Pages/Quiz.vue";
import Results from "./Pages/Results.vue";
import {onMounted} from "vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    quizType: String,
    model: Object,
});

const QuizzerStore = useQuizzerStore();

onMounted(() => {
    QuizzerStore.reset();
    QuizzerStore.data.quizType = props.quizType;
    QuizzerStore.data.model = props.model;
});
</script>
<template>
    <Head :title="`Academy: Quizzer (${quizType})`"/>
    <div id="app-head" v-if="QuizzerStore.data.step ==='setup'">
        <Link :href="route('quizzer.index')"><h1>Quizzer</h1></Link>
    </div>
    <div id="app-body">
        <Setup v-if="QuizzerStore.data.step ==='setup'"/>
        <Quiz v-if="QuizzerStore.data.step ==='quiz'"/>
        <Results v-if="QuizzerStore.data.step ==='results'"/>
    </div>
</template>
